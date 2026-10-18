<?php

namespace App\Http\Controllers;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Http\Requests\ChangeServiceOrderStatusRequest;
use App\Http\Requests\StoreServiceOrderRequest;
use App\Http\Requests\UpdateServiceOrderRequest;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\ServiceOrder;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request, TicketService $ticketService): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'priority' => $request->string('priority')->toString(),
        ];

        return view('service-orders.index', [
            'orders' => $ticketService->paginate(...$filters),
            'filters' => $filters,
            'statuses' => EstadoTicket::cases(),
            'priorities' => PrioridadTicket::cases(),
        ]);
    }

    public function create(): View
    {
        return view('service-orders.create', $this->formOptions());
    }

    public function store(StoreServiceOrderRequest $request, TicketService $ticketService): RedirectResponse
    {
        $order = $ticketService->create($request->validated(), $request->user());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', "La orden {$order->order_code} fue creada correctamente.");
    }

    public function show(ServiceOrder $serviceOrder): View
    {
        $serviceOrder->load([
            'client',
            'equipment',
            'diagnosis',
            'repair',
            'histories.user',
        ]);

        return view('service-orders.show', [
            'order' => $serviceOrder,
            'nextStatuses' => app(TicketService::class)->nextStatuses($serviceOrder),
        ]);
    }

    public function edit(ServiceOrder $serviceOrder): View
    {
        return view('service-orders.edit', [
            ...$this->formOptions(),
            'order' => $serviceOrder,
        ]);
    }

    public function update(
        UpdateServiceOrderRequest $request,
        ServiceOrder $serviceOrder,
        TicketService $ticketService,
    ): RedirectResponse {
        $ticketService->update($serviceOrder, $request->validated(), $request->user());

        return redirect()
            ->route('orders.show', $serviceOrder)
            ->with('success', "La orden {$serviceOrder->order_code} fue actualizada.");
    }

    public function destroy(ServiceOrder $serviceOrder, TicketService $ticketService): RedirectResponse
    {
        $code = $serviceOrder->order_code;
        $ticketService->delete($serviceOrder);

        return redirect()
            ->route('orders.index')
            ->with('success', "La orden {$code} fue eliminada.");
    }

    public function changeStatus(
        ChangeServiceOrderStatusRequest $request,
        ServiceOrder $serviceOrder,
        TicketService $ticketService,
    ): RedirectResponse {
        $ticketService->changeStatus(
            $serviceOrder,
            $request->validated('status'),
            $request->validated('notes'),
            $request->user(),
        );

        return redirect()
            ->route('orders.show', $serviceOrder)
            ->with('success', 'El estado de la orden fue actualizado.');
    }

    /**
     * @return array{clients: Collection, equipments: Collection}
     */
    private function formOptions(): array
    {
        return [
            'clients' => Client::query()->where('active', true)->orderBy('name')->get(),
            'equipments' => Equipment::query()->with('client')->orderBy('brand')->orderBy('model')->get(),
            'priorities' => PrioridadTicket::cases(),
        ];
    }
}
