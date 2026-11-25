@extends('layouts.app')

@section('title', $order->order_code.' · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Portal del cliente</p>
            <h1>{{ $order->order_code }}</h1>
            <p class="muted">{{ $order->equipment->brand }} {{ $order->equipment->model }} · {{ $order->equipment->equipment_type }}</p>
        </div>
        <div class="actions">
            <a class="button button-secondary" href="{{ route('client.orders.index') }}">Volver a mis órdenes</a>
            <span class="badge badge-{{ $order->status->color() }}">{{ $order->status->etiqueta() }}</span>
        </div>
    </div>

    <div class="two-columns">
        <div class="stack">
            <section class="card">
                <div class="card-header"><h2>Resumen del servicio</h2></div>
                <div class="card-body">
                    <dl class="detail-grid">
                        <div class="detail"><dt>Equipo</dt><dd>{{ $order->equipment->brand }} {{ $order->equipment->model }}<br><small class="muted">{{ $order->equipment->serial_number ?: 'Serie no registrada' }}</small></dd></div>
                        <div class="detail"><dt>Tipo de servicio</dt><dd>{{ $order->service_type === 'repair' ? 'Reparación' : 'Mantenimiento' }}</dd></div>
                        <div class="detail"><dt>Prioridad</dt><dd><span class="badge badge-{{ $order->priority->color() }}">{{ $order->priority->etiqueta() }}</span></dd></div>
                        <div class="detail"><dt>Recibido</dt><dd>{{ $order->received_at?->format('d/m/Y H:i') ?? '—' }}</dd></div>
                        <div class="detail"><dt>Costo estimado</dt><dd>{{ $order->estimated_cost !== null ? '$'.number_format((float) $order->estimated_cost, 2) : 'Pendiente' }}</dd></div>
                        <div class="detail"><dt>Costo final</dt><dd>{{ $order->final_cost !== null ? '$'.number_format((float) $order->final_cost, 2) : 'Pendiente' }}</dd></div>
                    </dl>
                    <div class="detail" style="margin-top: 1rem;"><dt>Problema reportado</dt><dd>{{ $order->reported_problem }}</dd></div>
                </div>
            </section>

            <section class="card">
                <div class="card-header"><h2>Diagnóstico y reparación</h2></div>
                <div class="card-body">
                    @if($order->diagnosis)
                        <div class="detail"><dt>Diagnóstico</dt><dd>{{ $order->diagnosis->diagnosis }}</dd></div>
                        <div class="detail" style="margin-top: .8rem;"><dt>Solución propuesta</dt><dd>{{ $order->diagnosis->proposed_solution ?: 'Sin información registrada.' }}</dd></div>
                    @else
                        <p class="muted">El diagnóstico todavía no está disponible.</p>
                    @endif

                    @if($order->repair)
                        <hr style="border: 0; border-top: 1px solid var(--line); margin: 1.5rem 0;">
                        <div class="detail"><dt>Trabajo realizado</dt><dd>{{ $order->repair->performed_work }}</dd></div>
                        <div class="detail" style="margin-top: .8rem;"><dt>Repuestos utilizados</dt><dd>{{ $order->repair->parts_used ?: 'No se registraron repuestos.' }}</dd></div>
                    @endif
                </div>
            </section>
        </div>

        <div class="stack">
            <section class="card">
                <div class="card-header"><h2>Historial de estados</h2></div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($order->histories as $history)
                            @php($historyStatus = \App\Enums\EstadoTicket::tryFrom($history->new_status))
                            <div class="timeline-item">
                                <strong>{{ $historyStatus?->etiqueta() ?? $history->new_status }}</strong>
                                <small>{{ $history->changed_at?->format('d/m/Y H:i') }}</small>
                            </div>
                        @empty
                            <p class="muted">Sin movimientos registrados.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="card">
                <div class="card-header"><h2>Archivos del servicio</h2></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.orders.attachments.store', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="field">
                            <label for="client_file">Cargar imagen o PDF</label>
                            <input id="client_file" type="file" name="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required>
                            <small class="muted">Máximo 10 MB. Se aceptan JPG, PNG, WEBP y PDF.</small>
                        </div>
                        <button class="button-primary" style="margin-top: .8rem; width: 100%;" type="submit">Cargar archivo</button>
                    </form>

                    @if($order->attachments->isNotEmpty())
                        <hr style="border: 0; border-top: 1px solid var(--line); margin: 1.5rem 0 1rem;">
                        <div class="stack" style="gap: .6rem;">
                            @foreach($order->attachments as $attachment)
                                <div class="detail">
                                    <a href="{{ route('client.orders.attachments.view', [$order, $attachment]) }}" target="_blank" rel="noopener">{{ $attachment->original_name }}</a>
                                    <small class="muted">{{ number_format($attachment->size / 1048576, 2) }} MB · {{ $attachment->created_at?->format('d/m/Y H:i') }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="muted" style="margin-top: 1rem;">Todavía no hay archivos asociados.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
