<?php

namespace App\Http\Controllers;

use App\Enums\Rol;
use App\Http\Requests\StoreServiceOrderAttachmentRequest;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ServiceOrderAttachmentController extends Controller
{
    public function store(
        StoreServiceOrderAttachmentRequest $request,
        ServiceOrder $serviceOrder,
    ): RedirectResponse {
        $file = $request->file('file');
        $disk = 'local';
        $path = $file->store('service-orders/'.$serviceOrder->id, $disk);

        $serviceOrder->attachments()->create([
            'uploaded_by_user_id' => $request->user()->id,
            'original_name' => basename($file->getClientOriginalName()),
            'path' => $path,
            'disk' => $disk,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'El archivo fue cargado correctamente.');
    }

    public function view(Request $request, ServiceOrder $serviceOrder, ServiceOrderAttachment $attachment): Response
    {
        $this->ensureAttachmentBelongsToOrder($serviceOrder, $attachment);
        $this->ensureCanView($request->user(), $serviceOrder);

        $disk = Storage::disk($attachment->disk);

        abort_unless($disk->exists($attachment->path), 404);

        return $disk->response(
            $attachment->path,
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type],
            'inline',
        );
    }

    public function destroy(Request $request, ServiceOrder $serviceOrder, ServiceOrderAttachment $attachment): RedirectResponse
    {
        abort_unless($request->user()?->tieneRol(Rol::Admin, Rol::Tecnico), 403);
        $this->ensureAttachmentBelongsToOrder($serviceOrder, $attachment);

        Storage::disk($attachment->disk)->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'El archivo fue eliminado.');
    }

    private function ensureAttachmentBelongsToOrder(
        ServiceOrder $serviceOrder,
        ServiceOrderAttachment $attachment,
    ): void {
        abort_unless((int) $attachment->service_order_id === (int) $serviceOrder->id, 404);
    }

    private function ensureCanView($user, ServiceOrder $serviceOrder): void
    {
        if ($user?->tieneRol(Rol::Admin, Rol::Tecnico)) {
            return;
        }

        abort_unless(
            $user?->tieneRol(Rol::Cliente)
                && (int) $user->client?->id === (int) $serviceOrder->client_id,
            403,
        );
    }
}
