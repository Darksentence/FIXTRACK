<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    public function index(Request $request): View
    {
        $client = $request->user()->client;

        if (! $client) {
            return view('client-portal.unlinked');
        }

        $orders = $this->ordersFor($client)
            ->with(['equipment', 'attachments'])
            ->latest('received_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('client-portal.orders.index', compact('client', 'orders'));
    }

    public function show(Request $request, int $serviceOrder): View
    {
        $client = $request->user()->client;

        abort_unless($client, 403, 'Tu cuenta aún no está vinculada a un cliente.');

        $order = $this->ordersFor($client)
            ->with([
                'equipment',
                'diagnosis',
                'repair',
                'attachments.uploader',
                'histories' => fn ($query) => $query->latest('changed_at'),
            ])
            ->findOrFail($serviceOrder);

        return view('client-portal.orders.show', compact('client', 'order'));
    }

    private function ordersFor(Client $client)
    {
        return ServiceOrder::query()->where('client_id', $client->id);
    }
}
