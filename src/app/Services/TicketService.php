<?php

namespace App\Services;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Models\Equipment;
use App\Models\OrderHistory;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;

class TicketService
{
    /**
     * Estados permitidos para evitar saltos de flujo accidentales.
     * Un ticket entregado o rechazado queda cerrado para este módulo.
     *
     * @var array<string, array<int, string>>
     */
    private const TRANSITIONS = [
        'pending_diagnosis' => ['waiting_authorization', 'rejected'],
        'waiting_authorization' => ['authorized', 'rejected'],
        'authorized' => ['in_repair', 'rejected'],
        'in_repair' => ['ready_for_pickup'],
        'ready_for_pickup' => ['delivered'],
        'rejected' => [],
        'delivered' => [],
    ];

    public function __construct(private readonly DatabaseManager $database) {}

    /**
     * Lista ordenes con filtros de interfaz y sus relaciones principales.
     */
    public function paginate(
        ?string $search = null,
        ?string $status = null,
        ?string $priority = null,
    ): LengthAwarePaginator {
        $query = ServiceOrder::query()
            ->with(['client', 'equipment'])
            ->latest('received_at')
            ->latest('id');

        if ($search !== null && trim($search) !== '') {
            $term = trim($search);

            $query->where(function ($orders) use ($term): void {
                $orders
                    ->where('order_code', 'like', "%{$term}%")
                    ->orWhereHas('client', fn ($clients) => $clients
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('client_code', 'like', "%{$term}%"))
                    ->orWhereHas('equipment', fn ($equipment) => $equipment
                        ->where('brand', 'like', "%{$term}%")
                        ->orWhere('model', 'like', "%{$term}%")
                        ->orWhere('serial_number', 'like', "%{$term}%"));
            });
        }

        if ($status !== null && EstadoTicket::tryFrom($status) !== null) {
            $query->where('status', $status);
        }

        if ($priority !== null && PrioridadTicket::tryFrom($priority) !== null) {
            $query->where('priority', $priority);
        }

        return $query->paginate(10)->withQueryString();
    }

    public function create(array $data, User $actor): ServiceOrder
    {
        return $this->database->transaction(function () use ($data, $actor): ServiceOrder {
            $this->assertEquipmentBelongsToClient($data['equipment_id'], $data['client_id']);

            $order = ServiceOrder::create([
                ...$data,
                'order_code' => $data['order_code'] ?? $this->nextOrderCode(),
                'status' => EstadoTicket::PendienteDiagnostico,
                'priority' => $data['priority'] ?? PrioridadTicket::Media,
                'authorization_status' => 'pending',
            ]);

            $this->recordHistory(
                $order,
                previousStatus: null,
                newStatus: EstadoTicket::PendienteDiagnostico->value,
                notes: 'Orden creada.',
                actor: $actor,
            );

            return $order->fresh(['client', 'equipment']);
        });
    }

    public function update(ServiceOrder $order, array $data, User $actor): ServiceOrder
    {
        return $this->database->transaction(function () use ($order, $data): ServiceOrder {
            $clientId = (int) ($data['client_id'] ?? $order->client_id);
            $equipmentId = (int) ($data['equipment_id'] ?? $order->equipment_id);

            $this->assertEquipmentBelongsToClient($equipmentId, $clientId);

            $order->fill([
                ...$data,
                'client_id' => $clientId,
                'equipment_id' => $equipmentId,
            ])->save();

            return $order->fresh(['client', 'equipment', 'diagnosis', 'repair', 'histories.user']);
        });
    }

    public function saveDiagnosis(ServiceOrder $order, array $data, User $actor): ServiceOrder
    {
        return $this->database->transaction(function () use ($order, $data, $actor): ServiceOrder {
            $order->diagnosis()->updateOrCreate([], [
                ...$data,
                'diagnosed_at' => now(),
            ]);

            $order->refresh();

            if ($order->status === EstadoTicket::PendienteDiagnostico) {
                $order = $this->changeStatus(
                    $order,
                    EstadoTicket::EsperandoAutorizacion,
                    'Diagnóstico registrado y pendiente de autorización.',
                    $actor,
                );
            }

            return $order->fresh(['client', 'equipment', 'diagnosis', 'repair', 'histories.user']);
        });
    }

    public function saveRepair(ServiceOrder $order, array $data, User $actor): ServiceOrder
    {
        return $this->database->transaction(function () use ($order, $data, $actor): ServiceOrder {
            $hasFinalCost = array_key_exists('final_cost', $data);
            $finalCost = $data['final_cost'] ?? null;
            unset($data['final_cost']);

            $order->repair()->updateOrCreate([], [
                ...$data,
                'started_at' => $data['started_at'] ?? null,
                'completed_at' => $data['completed_at'] ?? null,
            ]);

            if ($hasFinalCost) {
                $order->final_cost = $finalCost;
                $order->save();
            }

            $order->refresh();

            if ($order->status === EstadoTicket::Autorizado) {
                $order = $this->changeStatus(
                    $order,
                    EstadoTicket::EnReparacion,
                    'Trabajo de reparación iniciado.',
                    $actor,
                );
            }

            return $order->fresh(['client', 'equipment', 'diagnosis', 'repair', 'histories.user']);
        });
    }

    public function changeStatus(
        ServiceOrder $order,
        EstadoTicket|string $newStatus,
        ?string $notes,
        User $actor,
    ): ServiceOrder {
        return $this->database->transaction(function () use ($order, $newStatus, $notes, $actor): ServiceOrder {
            $target = $newStatus instanceof EstadoTicket
                ? $newStatus
                : EstadoTicket::from($newStatus);
            $current = $order->status instanceof EstadoTicket
                ? $order->status
                : EstadoTicket::from($order->status);

            if ($current === $target) {
                throw ValidationException::withMessages([
                    'status' => 'La orden ya se encuentra en ese estado.',
                ]);
            }

            if (! in_array($target->value, self::TRANSITIONS[$current->value] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => "No se permite pasar de {$current->etiqueta()} a {$target->etiqueta()}.",
                ]);
            }

            $updates = [
                'status' => $target,
            ];

            if ($target === EstadoTicket::Autorizado) {
                $updates['authorization_status'] = 'authorized';
                $updates['authorized_at'] = now();
            }

            if ($target === EstadoTicket::Rechazado) {
                $updates['authorization_status'] = 'rejected';
            }

            if ($target === EstadoTicket::ListoParaRetiro) {
                $updates['completed_at'] = now();
            }

            if ($target === EstadoTicket::Entregado) {
                $updates['delivered_at'] = now();
            }

            $order->fill($updates)->save();

            $this->recordHistory(
                $order,
                previousStatus: $current->value,
                newStatus: $target->value,
                notes: $notes,
                actor: $actor,
            );

            return $order->fresh(['client', 'equipment', 'diagnosis', 'repair', 'histories.user']);
        });
    }

    public function delete(ServiceOrder $order): void
    {
        $this->database->transaction(fn () => $order->delete());
    }

    /**
     * @return array<int, EstadoTicket>
     */
    public function nextStatuses(ServiceOrder $order): array
    {
        $current = $order->status instanceof EstadoTicket
            ? $order->status
            : EstadoTicket::from($order->status);

        return array_values(array_filter(
            EstadoTicket::cases(),
            fn (EstadoTicket $status) => in_array($status->value, self::TRANSITIONS[$current->value] ?? [], true),
        ));
    }

    private function assertEquipmentBelongsToClient(int $equipmentId, int $clientId): void
    {
        if (! Equipment::query()
            ->whereKey($equipmentId)
            ->where('client_id', $clientId)
            ->exists()) {
            throw ValidationException::withMessages([
                'equipment_id' => 'El equipo seleccionado no pertenece al cliente indicado.',
            ]);
        }
    }

    private function recordHistory(
        ServiceOrder $order,
        ?string $previousStatus,
        string $newStatus,
        ?string $notes,
        User $actor,
    ): OrderHistory {
        return $order->histories()->create([
            'user_id' => $actor->id,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_at' => now(),
        ]);
    }

    private function nextOrderCode(): string
    {
        $lastCode = ServiceOrder::query()
            ->where('order_code', 'like', 'A-%')
            ->orderByDesc('id')
            ->value('order_code');

        $lastNumber = 0;

        if (is_string($lastCode) && preg_match('/^A-(\d+)$/', $lastCode, $matches) === 1) {
            $lastNumber = (int) $matches[1];
        }

        return sprintf('A-%04d', $lastNumber + 1);
    }
}
