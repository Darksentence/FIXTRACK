@extends('layouts.app')

@section('title', $order->order_code.' · FIXTRACK')

@section('content')
    @php
        /*
         * FASE 3 - PARTE 1: CONTROL DE ÓRDENES ENTREGADAS
         *
         * Se comprueba si la orden ya fue entregada.
         * Cuando esto ocurre, la información de diagnóstico
         * y reparación se presenta únicamente para consulta.
         *
         * Este control evita mostrar formularios editables,
         * pero la seguridad debe reforzarse en TicketService.
         */
        $isDelivered = $order->status === \App\Enums\EstadoTicket::Entregado;
    @endphp

    {{-- =========================================================
         SECCIÓN 1: ENCABEZADO Y NAVEGACIÓN
         Muestra el código de la orden, el cliente y el equipo.
         Permite regresar al listado o editar órdenes activas.
         ========================================================= --}}
    <div class="page-heading">
        <div>
            <p class="eyebrow">Vista detallada</p>
            <h1>{{ $order->order_code }}</h1>
            <p class="muted">
                {{ $order->client->name }} ·
                {{ $order->equipment->brand }}
                {{ $order->equipment->model }}
            </p>
        </div>

        <div class="actions">
            <a class="button button-secondary" href="{{ route('orders.index') }}">
                Volver
            </a>

            {{-- FASE 3 - PARTE 1: no ofrecer edición de órdenes entregadas. --}}
            @unless($isDelivered)
                <a class="button button-primary" href="{{ route('orders.edit', $order) }}">
                    Editar
                </a>
            @endunless
        </div>
    </div>

    <div class="two-columns">
        <div class="stack">

            {{-- =====================================================
                 SECCIÓN 2: INFORMACIÓN GENERAL DE LA ORDEN
                 Presenta los datos registrados durante la recepción:
                 cliente, equipo, servicio, fecha y costos.
                 ===================================================== --}}
            <section class="card">
                <div class="card-header">
                    <h2>Información de la orden</h2>

                    <div class="actions">
                        {{-- Se utilizan los colores y etiquetas definidos en los enums. --}}
                        <span class="badge badge-{{ $order->status->color() }}">
                            {{ $order->status->etiqueta() }}
                        </span>

                        <span class="badge badge-{{ $order->priority->color() }}">
                            {{ $order->priority->etiqueta() }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <dl class="detail-grid">
                        <div class="detail">
                            <dt>Cliente</dt>
                            <dd>
                                {{ $order->client->client_code }} ·
                                {{ $order->client->name }}
                            </dd>
                        </div>

                        <div class="detail">
                            <dt>Equipo</dt>
                            <dd>
                                {{ $order->equipment->brand }}
                                {{ $order->equipment->model }}
                                <br>

                                <small class="muted">
                                    {{ $order->equipment->equipment_type }}
                                    {{ $order->equipment->serial_number ? ' · '.$order->equipment->serial_number : '' }}
                                </small>
                            </dd>
                        </div>

                        <div class="detail">
                            <dt>Tipo de servicio</dt>
                            <dd>
                                {{ $order->service_type === 'repair' ? 'Reparación' : 'Mantenimiento' }}
                            </dd>
                        </div>

                        <div class="detail">
                            <dt>Recibido</dt>
                            <dd>
                                {{ $order->received_at?->format('d/m/Y H:i') ?? '—' }}
                            </dd>
                        </div>

                        <div class="detail">
                            <dt>Costo estimado</dt>
                            <dd>
                                {{ $order->estimated_cost !== null ? '$'.number_format((float) $order->estimated_cost, 2) : 'Pendiente' }}
                            </dd>
                        </div>

                        {{-- FASE 3: mostrar el costo final cuando exista. --}}
                        <div class="detail">
                            <dt>Costo final</dt>
                            <dd>
                                {{ $order->final_cost !== null ? '$'.number_format((float) $order->final_cost, 2) : 'Pendiente' }}
                            </dd>
                        </div>
                    </dl>

                    {{-- Descripción original del problema reportado por el cliente. --}}
                    <div class="detail" style="margin-top: 1rem;">
                        <dt>Problema reportado</dt>
                        <dd>{{ $order->reported_problem }}</dd>
                    </div>
                </div>
            </section>

            {{-- =====================================================
                 SECCIÓN 3: DIAGNÓSTICO Y REPARACIÓN

                 INTEGRACIÓN DE PARTES 1 Y 4:
                 - Parte 1: órdenes entregadas en solo lectura.
                 - Parte 4: validaciones visuales de formularios.

                 Los formularios solamente aparecen cuando la orden
                 no está entregada.
                 ===================================================== --}}
            <section class="card">
                <div class="card-header">
                    <h2>Diagnóstico y reparación</h2>
                </div>

                <div class="card-body">

                    @if($isDelivered)

                        {{--
                            FASE 3 - PARTE 1: SOLO LECTURA
                            La información se presenta sin campos editables.
                            Se conservan los datos históricos registrados.
                        --}}
                        <p class="note">
                            Esta orden ya fue entregada. El diagnóstico y la reparación
                            están disponibles únicamente para consulta.
                        </p>

                        <h3 style="margin: 1rem 0 .8rem;">
                            Diagnóstico registrado
                        </h3>

                        @if($order->diagnosis)
                            <div class="detail">
                                <dt>Diagnóstico</dt>
                                <dd>{{ $order->diagnosis->diagnosis }}</dd>
                            </div>

                            <div class="detail" style="margin-top: .8rem;">
                                <dt>Solución propuesta</dt>
                                <dd>
                                    {{ $order->diagnosis->proposed_solution ?: 'Sin información registrada.' }}
                                </dd>
                            </div>

                            <div class="detail" style="margin-top: .8rem;">
                                <dt>Observaciones</dt>
                                <dd>
                                    {{ $order->diagnosis->observations ?: 'Sin observaciones.' }}
                                </dd>
                            </div>
                        @else
                            <p class="muted">
                                No se registró un diagnóstico.
                            </p>
                        @endif

                        <hr style="border: 0; border-top: 1px solid var(--line); margin: 1.5rem 0;">

                        <h3 style="margin: 0 0 .8rem;">
                            Reparación registrada
                        </h3>

                        @if($order->repair)
                            <div class="detail">
                                <dt>Trabajo realizado</dt>
                                <dd>{{ $order->repair->performed_work }}</dd>
                            </div>

                            <div class="detail" style="margin-top: .8rem;">
                                <dt>Repuestos utilizados</dt>
                                <dd>
                                    {{ $order->repair->parts_used ?: 'No se registraron repuestos.' }}
                                </dd>
                            </div>

                            <div class="detail" style="margin-top: .8rem;">
                                <dt>Observaciones</dt>
                                <dd>
                                    {{ $order->repair->observations ?: 'Sin observaciones.' }}
                                </dd>
                            </div>

                            <div class="detail" style="margin-top: .8rem;">
                                <dt>Inicio</dt>
                                <dd>
                                    {{ $order->repair->started_at?->format('d/m/Y H:i') ?? 'No registrado' }}
                                </dd>
                            </div>

                            <div class="detail" style="margin-top: .8rem;">
                                <dt>Finalización</dt>
                                <dd>
                                    {{ $order->repair->completed_at?->format('d/m/Y H:i') ?? 'No registrada' }}
                                </dd>
                            </div>
                        @else
                            <p class="muted">
                                No se registró una reparación.
                            </p>
                        @endif

                    @else

                        {{-- ÓRDENES ACTIVAS: conservar los formularios originales. --}}

                        {{-- =================================================
                             FORMULARIO DE DIAGNÓSTICO

                             FASE 3 - PARTE 4:
                             Se incorpora minlength para exigir al menos
                             cinco caracteres en el diagnóstico.
                             aria-invalid identifica errores de validación.
                             partials.field-error muestra el mensaje
                             correspondiente cuando Laravel rechaza un dato.
                             ================================================= --}}
                        <form method="POST" action="{{ route('orders.diagnosis', $order) }}">
                            @csrf

                            <h3 style="margin: 0 0 .8rem;">
                                {{ $order->diagnosis ? 'Editar diagnóstico' : 'Registrar diagnóstico' }}
                            </h3>

                            <div class="field">
                                <label for="diagnosis">Diagnóstico *</label>

                                <textarea
                                    id="diagnosis"
                                    name="diagnosis"
                                    required
                                    minlength="5"
                                    aria-invalid="{{ $errors->has('diagnosis') ? 'true' : 'false' }}"
                                >{{ old('diagnosis', $order->diagnosis?->diagnosis ?? '') }}</textarea>

                                @include('partials.field-error', ['field' => 'diagnosis'])
                            </div>

                            <div class="form-grid" style="margin-top: .8rem;">
                                <div class="field">
                                    <label for="proposed_solution">
                                        Solución propuesta
                                    </label>

                                    <textarea
                                        id="proposed_solution"
                                        name="proposed_solution"
                                        style="min-height: 90px;"
                                        aria-invalid="{{ $errors->has('proposed_solution') ? 'true' : 'false' }}"
                                    >{{ old('proposed_solution', $order->diagnosis?->proposed_solution ?? '') }}</textarea>

                                    @include('partials.field-error', ['field' => 'proposed_solution'])
                                </div>

                                <div class="field">
                                    <label for="diagnosis_observations">
                                        Observaciones
                                    </label>

                                    <textarea
                                        id="diagnosis_observations"
                                        name="observations"
                                        style="min-height: 90px;"
                                    >{{ old('observations', $order->diagnosis?->observations ?? '') }}</textarea>
                                </div>
                            </div>

                            <button class="button-primary" style="margin-top: .8rem;" type="submit">
                                Guardar diagnóstico
                            </button>
                        </form>

                        <hr style="border: 0; border-top: 1px solid var(--line); margin: 1.5rem 0;">

                        {{-- =================================================
                             FORMULARIO DE REPARACIÓN

                             FASE 3 - PARTE 4:
                             Se valida el trabajo realizado y se muestran
                             los errores relacionados con el costo final.

                             Se conservan los campos originales:
                             repuestos, observaciones, fechas y costo.
                             ================================================= --}}
                        <form method="POST" action="{{ route('orders.repair', $order) }}">
                            @csrf

                            <h3 style="margin: 0 0 .8rem;">
                                {{ $order->repair ? 'Editar reparación' : 'Registrar reparación' }}
                            </h3>

                            <div class="field">
                                <label for="performed_work">
                                    Trabajo realizado *
                                </label>

                                <textarea
                                    id="performed_work"
                                    name="performed_work"
                                    required
                                    minlength="5"
                                    aria-invalid="{{ $errors->has('performed_work') ? 'true' : 'false' }}"
                                >{{ old('performed_work', $order->repair?->performed_work ?? '') }}</textarea>

                                @include('partials.field-error', ['field' => 'performed_work'])
                            </div>

                            <div class="form-grid" style="margin-top: .8rem;">
                                <div class="field">
                                    <label for="parts_used">
                                        Repuestos utilizados
                                    </label>

                                    <textarea
                                        id="parts_used"
                                        name="parts_used"
                                        style="min-height: 90px;"
                                    >{{ old('parts_used', $order->repair?->parts_used ?? '') }}</textarea>
                                </div>

                                <div class="field">
                                    <label for="repair_observations">
                                        Observaciones
                                    </label>

                                    <textarea
                                        id="repair_observations"
                                        name="observations"
                                        style="min-height: 90px;"
                                    >{{ old('observations', $order->repair?->observations ?? '') }}</textarea>
                                </div>

                                <div class="field">
                                    <label for="started_at">
                                        Inicio
                                    </label>

                                    <input
                                        id="started_at"
                                        type="datetime-local"
                                        name="started_at"
                                        value="{{ old('started_at', $order->repair?->started_at?->format('Y-m-d\\TH:i') ?? '') }}"
                                    >
                                </div>

                                <div class="field">
                                    <label for="completed_at">
                                        Finalización
                                    </label>

                                    <input
                                        id="completed_at"
                                        type="datetime-local"
                                        name="completed_at"
                                        value="{{ old('completed_at', $order->repair?->completed_at?->format('Y-m-d\\TH:i') ?? '') }}"
                                    >
                                </div>

                                <div class="field">
                                    <label for="final_cost">
                                        Costo final del servicio
                                    </label>

                                    <input
                                        id="final_cost"
                                        type="number"
                                        name="final_cost"
                                        min="0"
                                        max="99999999.99"
                                        step="0.01"
                                        value="{{ old('final_cost', $order->final_cost ?? '') }}"
                                        placeholder="Se define al terminar el trabajo"
                                        aria-invalid="{{ $errors->has('final_cost') ? 'true' : 'false' }}"
                                    >

                                    @include('partials.field-error', ['field' => 'final_cost'])
                                </div>
                            </div>

                            <button class="button-primary" style="margin-top: .8rem;" type="submit">
                                Guardar reparación
                            </button>
                        </form>
                    @endif
                </div>
            </section>

            {{-- =====================================================
                 SECCIÓN 4: ARCHIVOS ADJUNTOS DEL SERVICIO

                 FASE 3 - PARTE 2:
                 Se incorpora la administración de evidencias
                 asociadas a las órdenes de servicio.

                 Funcionalidades:
                 - Cargar imágenes y documentos PDF.
                 - Consultar los archivos registrados.
                 - Mostrar el usuario y fecha de carga.
                 - Eliminar archivos mediante una petición DELETE.

                 CORRECCIÓN DE INTEGRACIÓN:
                 Los formularios de diagnóstico y reparación
                 permanecen en su propia sección.
                 ===================================================== --}}
            <section class="card">
                <div class="card-header">
                    <h2>Archivos del servicio</h2>
                </div>

                <div class="card-body">

                    {{-- Formulario de carga con enctype para enviar archivos. --}}
                    <form
                        method="POST"
                        action="{{ route('orders.attachments.store', $order) }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="field">
                            <label for="staff_file">
                                Cargar imagen o PDF
                            </label>

                            <input
                                id="staff_file"
                                type="file"
                                name="file"
                                accept=".jpg,.jpeg,.png,.webp,.pdf"
                                required
                            >

                            <small class="muted">
                                Máximo 10 MB. Se aceptan JPG, PNG, WEBP y PDF.
                            </small>
                        </div>

                        <button
                            class="button-primary"
                            style="margin-top: .8rem; width: 100%;"
                            type="submit"
                        >
                            Cargar archivo
                        </button>
                    </form>

                    {{-- Listado de archivos existentes de la orden. --}}
                    @if($order->attachments->isNotEmpty())

                        <hr style="border: 0; border-top: 1px solid var(--line); margin: 1.5rem 0 1rem;">

                        <div class="stack" style="gap: .6rem;">
                            @foreach($order->attachments as $attachment)
                                <div class="detail">
                                    <div class="actions" style="justify-content: space-between;">

                                        {{-- Abre el archivo en otra pestaña. --}}
                                        <a
                                            href="{{ route('orders.attachments.view', [$order, $attachment]) }}"
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            {{ $attachment->original_name }}
                                        </a>

                                        {{-- Solicita confirmación antes de eliminar. --}}
                                        <form
                                            class="inline"
                                            method="POST"
                                            action="{{ route('orders.attachments.destroy', [$order, $attachment]) }}"
                                            onsubmit="return confirm('¿Eliminar este archivo?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button class="button-danger button-small" type="submit">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Información de tamaño, usuario y fecha. --}}
                                    <small class="muted">
                                        {{ number_format($attachment->size / 1048576, 2) }} MB
                                        · {{ $attachment->uploader?->name ?? 'Sistema' }}
                                        · {{ $attachment->created_at?->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="muted" style="margin-top: 1rem;">
                            Todavía no hay archivos asociados.
                        </p>
                    @endif
                </div>
            </section>
        </div>

        <div class="stack">

            {{-- =====================================================
                 SECCIÓN 5: ACTUALIZACIÓN DEL ESTADO

                 Permite avanzar la orden según las transiciones
                 disponibles en la lógica de negocio.

                 FASE 3 - PARTE 1:
                 Se bloquea visualmente el cambio de estado
                 cuando la orden ya fue entregada.
                 ===================================================== --}}
            <section class="card">
                <div class="card-header">
                    <h2>Actualizar estado</h2>
                </div>

                <div class="card-body">
                    @if($isDelivered)
                        <p class="note">
                            Esta orden está entregada y no admite nuevos cambios de estado.
                        </p>

                    @elseif(count($nextStatuses) > 0)
                        <form method="POST" action="{{ route('orders.status', $order) }}">
                            @csrf
                            @method('PATCH')

                            <div class="field">
                                <label for="status">
                                    Siguiente estado
                                </label>

                                <select id="status" name="status" required>
                                    <option value="">
                                        Selecciona un estado
                                    </option>

                                    @foreach($nextStatuses as $nextStatus)
                                        <option value="{{ $nextStatus->value }}">
                                            {{ $nextStatus->etiqueta() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field" style="margin-top: .8rem;">
                                <label for="notes">
                                    Nota del cambio
                                </label>

                                <textarea
                                    id="notes"
                                    name="notes"
                                    style="min-height: 90px;"
                                    placeholder="Opcional: explica el avance o la autorización"
                                ></textarea>
                            </div>

                            <button
                                class="button-primary"
                                style="margin-top: .8rem; width: 100%;"
                                type="submit"
                            >
                                Guardar estado
                            </button>
                        </form>
                    @else
                        <p class="note">
                            Esta orden está cerrada y no tiene transiciones disponibles.
                        </p>
                    @endif
                </div>
            </section>

            {{-- =====================================================
                 SECCIÓN 6: HISTORIAL DE LA ORDEN

                 Muestra los cambios de estado registrados.
                 Cada movimiento incluye fecha, usuario y
                 observaciones, cuando estén disponibles.

                 Se ordena del movimiento más reciente al antiguo.
                 ===================================================== --}}
            <section class="card">
                <div class="card-header">
                    <h2>Historial</h2>
                </div>

                <div class="card-body">
                    <div class="timeline">
                        @forelse($order->histories->sortByDesc('changed_at') as $history)
                            @php($historyStatus = \App\Enums\EstadoTicket::tryFrom($history->new_status))

                            <div class="timeline-item">
                                <strong>
                                    {{ $historyStatus?->etiqueta() ?? $history->new_status }}
                                </strong>

                                <small>
                                    {{ $history->changed_at?->format('d/m/Y H:i') }}
                                    · {{ $history->user?->name ?? 'Sistema' }}
                                </small>

                                @if($history->notes)
                                    <p class="muted" style="margin: .3rem 0 0;">
                                        {{ $history->notes }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="muted">
                                Sin movimientos registrados.
                            </p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection