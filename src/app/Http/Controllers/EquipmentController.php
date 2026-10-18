<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Client;
use App\Models\Equipment;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $clientId = $request->integer('client_id') ?: null;

        $equipments = Equipment::query()
            ->with('client')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($equipments) use ($search): void {
                    $equipments
                        ->where('equipment_type', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhereHas('client', fn ($clients) => $clients->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('equipments.index', [
            'equipments' => $equipments,
            'clients' => Client::query()->orderBy('name')->get(),
            'search' => $search,
            'clientId' => $clientId,
        ]);
    }

    public function create(): View
    {
        return view('equipments.create', ['clients' => Client::query()->where('active', true)->orderBy('name')->get()]);
    }

    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        Equipment::create($request->validated());

        return redirect()->route('equipments.index')->with('success', 'El equipo fue registrado.');
    }

    public function edit(Equipment $equipment): View
    {
        return view('equipments.edit', [
            'equipment' => $equipment,
            'clients' => Client::query()->where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $equipment->update($request->validated());

        return redirect()->route('equipments.index')->with('success', 'El equipo fue actualizado.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        try {
            $equipment->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar un equipo que tiene órdenes asociadas.');
        }

        return redirect()->route('equipments.index')->with('success', 'El equipo fue eliminado.');
    }
}
