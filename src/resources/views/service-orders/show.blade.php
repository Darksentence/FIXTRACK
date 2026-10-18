@extends('layouts.app')

@section('title', $order->order_code.' · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Vista detallada</p>
            <h1>{{ $order->order_code }}</h1>
            <p class="muted">{{ $order->client->name }} · {{ $order->equipment->brand }} {{ $order->equipment->model }}</p>
        </div>
        <div class="actions">
            <a class="button button-secondary" href="{{ route('orders.index') }}">Volver</a>
            <a class="button button-primary" href="{{ route('orders.edit', $order) }}">Editar</a>
        </div>
    </div>

    <div class="two-columns">
        <div class="stack">
            <section class="card">
                <div class="card-header">
                    <h2>Información de la orden</h2>
                    <div class="actions">
                        <span class="badge badge-{{ $order->status->color() }}">{{ $order->status->etiqueta() }}</span>
                        <span class="badge badge-{{ $order->priority->color() }}">{{ $order->priority->etiqueta() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="detail-grid">
                        <div class="detail"><dt>Cliente</dt><dd>{{ $order->client->client_code }} · {{ $order->client->name }}</dd></div>
                        <div class="detail"><dt>Equipo</dt><dd>{{ $order->equipment->brand }} {{ $order->equipment->model }}<br><small class="muted">{{ $order->equipment->equipment_type }}{{ $order->equipment->serial_number ? ' · '.$order->equipment->serial_number : '' }}</small></dd></div>
                        <div class="detail"><dt>Tipo de servicio</dt><dd>{{ $order->service_type === 'repair' ? 'Reparación' : 'Mantenimiento' }}</dd></div>
                        <div class="detail"><dt>Recibido</dt><dd>{{ $order->received_at?->format('d/m/Y H:i') ?? '—' }}</dd></div>
                        <div class="detail"><dt>Costo estimado</dt><dd>{{ $order->estimated_cost !== null ? '$'.number_format((float) $order->estimated_cost, 2) : 'Pendiente' }}</dd></div>
                    </dl>
                    <div class="detail" style="margin-top: 1rem;"><dt>Problema reportado</dt><dd>{{ $order->reported_problem }}</dd></div>
                </div>
            </section>

            <section class="card">
                <div class="card-header"><h2>Diagnóstico y reparación</h2></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.diagnosis', $order) }}">
                        @csrf
                        <h3 style="margin: 0 0 .8rem;">{{ $order->diagnosis ? 'Editar diagnóstico' : 'Registrar diagnóstico' }}</h3>
                        <div class="field"><label for="diagnosis">Diagnóstico *</label><textarea id="diagnosis" name="diagnosis" required>{{ old('diagnosis', $order->diagnosis?->diagnosis ?? '') }}</textarea></div>
                        <div class="form-grid" style="margin-top: .8rem;">
                            <div class="field"><label for="proposed_solution">Solución propuesta</label><textarea id="proposed_solution" name="proposed_solution" style="min-height: 90px;">{{ old('proposed_solution', $order->diagnosis?->proposed_solution ?? '') }}</textarea></div>
                            <div class="field"><label for="diagnosis_observations">Observaciones</label><textarea id="diagnosis_observations" name="observations" style="min-height: 90px;">{{ old('observations', $order->diagnosis?->observations ?? '') }}</textarea></div>
                        </div>
                        <button class="button-primary" style="margin-top: .8rem;" type="submit">Guardar diagnóstico</button>
                    </form>

                    <hr style="border: 0; border-top: 1px solid var(--line); margin: 1.5rem 0;">

                    <form method="POST" action="{{ route('orders.repair', $order) }}">
                        @csrf
                        <h3 style="margin: 0 0 .8rem;">{{ $order->repair ? 'Editar reparación' : 'Registrar reparación' }}</h3>
                        <div class="field"><label for="performed_work">Trabajo realizado *</label><textarea id="performed_work" name="performed_work" required>{{ old('performed_work', $order->repair?->performed_work ?? '') }}</textarea></div>
                        <div class="form-grid" style="margin-top: .8rem;">
                            <div class="field"><label for="parts_used">Repuestos utilizados</label><textarea id="parts_used" name="parts_used" style="min-height: 90px;">{{ old('parts_used', $order->repair?->parts_used ?? '') }}</textarea></div>
                            <div class="field"><label for="repair_observations">Observaciones</label><textarea id="repair_observations" name="observations" style="min-height: 90px;">{{ old('observations', $order->repair?->observations ?? '') }}</textarea></div>
                            <div class="field"><label for="started_at">Inicio</label><input id="started_at" type="datetime-local" name="started_at" value="{{ old('started_at', $order->repair?->started_at?->format('Y-m-d\\TH:i') ?? '') }}"></div>
                            <div class="field"><label for="completed_at">Finalización</label><input id="completed_at" type="datetime-local" name="completed_at" value="{{ old('completed_at', $order->repair?->completed_at?->format('Y-m-d\\TH:i') ?? '') }}"></div>
                            <div class="field"><label for="final_cost">Costo final del servicio</label><input id="final_cost" type="number" name="final_cost" min="0" max="99999999.99" step="0.01" value="{{ old('final_cost', $order->final_cost ?? '') }}" placeholder="Se define al terminar el trabajo"></div>
                        </div>
                        <button class="button-primary" style="margin-top: .8rem;" type="submit">Guardar reparación</button>
                    </form>
                </div>
            </section>
        </div>

        <div class="stack">
            <section class="card">
                <div class="card-header"><h2>Actualizar estado</h2></div>
                <div class="card-body">
                    @if(count($nextStatuses) > 0)
                        <form method="POST" action="{{ route('orders.status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <div class="field">
                                <label for="status">Siguiente estado</label>
                                <select id="status" name="status" required>
                                    <option value="">Selecciona un estado</option>
                                    @foreach($nextStatuses as $nextStatus)
                                        <option value="{{ $nextStatus->value }}">{{ $nextStatus->etiqueta() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field" style="margin-top: .8rem;">
                                <label for="notes">Nota del cambio</label>
                                <textarea id="notes" name="notes" style="min-height: 90px;" placeholder="Opcional: explica el avance o la autorización"></textarea>
                            </div>
                            <button class="button-primary" style="margin-top: .8rem; width: 100%;" type="submit">Guardar estado</button>
                        </form>
                    @else
                        <p class="note">Esta orden está cerrada y no tiene transiciones disponibles.</p>
                    @endif
                </div>
            </section>

            <section class="card">
                <div class="card-header"><h2>Historial</h2></div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($order->histories->sortByDesc('changed_at') as $history)
                            @php($historyStatus = \App\Enums\EstadoTicket::tryFrom($history->new_status))
                            <div class="timeline-item">
                                <strong>{{ $historyStatus?->etiqueta() ?? $history->new_status }}</strong>
                                <small>{{ $history->changed_at?->format('d/m/Y H:i') }} · {{ $history->user?->name ?? 'Sistema' }}</small>
                                @if($history->notes)<p class="muted" style="margin: .3rem 0 0;">{{ $history->notes }}</p>@endif
                            </div>
                        @empty
                            <p class="muted">Sin movimientos registrados.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
