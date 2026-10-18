<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $clients = Client::query()
            ->withCount(['equipments', 'serviceOrders'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($clients) use ($search): void {
                    $clients
                        ->where('client_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create([
            ...$request->validated(),
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('clients.index')->with('success', "El cliente {$client->client_code} fue creado.");
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update([
            ...$request->validated(),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('clients.index')->with('success', "El cliente {$client->client_code} fue actualizado.");
    }

    public function destroy(Client $client): RedirectResponse
    {
        $code = $client->client_code;

        try {
            $client->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar un cliente que tiene equipos u órdenes asociadas.');
        }

        return redirect()->route('clients.index')->with('success', "El cliente {$code} fue eliminado.");
    }
}
