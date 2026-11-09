@php
    $editing = isset($order);
    $selectedClient = old('client_id', $order->client_id ?? '');
    $selectedEquipment = old('equipment_id', $order->equipment_id ?? '');
@endphp

<div class="form-grid">
    <div class="field">
        <label for="client_id">Cliente *</label>
        <select id="client_id" name="client_id" required aria-invalid="{{ $errors->has('client_id') ? 'true' : 'false' }}">
            <option value="">Selecciona un cliente</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected((string) $selectedClient === (string) $client->id)>
                    {{ $client->client_code }} · {{ $client->name }}
                </option>
            @endforeach
        </select>
        @include('partials.field-error', ['field' => 'client_id'])
    </div>

    <div class="field">
        <label for="equipment_id">Equipo *</label>
        <select id="equipment_id" name="equipment_id" required aria-invalid="{{ $errors->has('equipment_id') ? 'true' : 'false' }}">
            <option value="">Selecciona primero un cliente</option>
            @foreach($equipments as $equipment)
                <option value="{{ $equipment->id }}" data-client-id="{{ $equipment->client_id }}" @selected((string) $selectedEquipment === (string) $equipment->id)>
                    {{ $equipment->brand }} {{ $equipment->model }} · {{ $equipment->equipment_type }}
                </option>
            @endforeach
        </select>
        @include('partials.field-error', ['field' => 'equipment_id'])
    </div>

    <div class="field">
        <label for="service_type">Tipo de servicio *</label>
        <select id="service_type" name="service_type" required aria-invalid="{{ $errors->has('service_type') ? 'true' : 'false' }}">
            <option value="repair" @selected(old('service_type', $order->service_type ?? '') === 'repair')>Reparación</option>
            <option value="maintenance" @selected(old('service_type', $order->service_type ?? '') === 'maintenance')>Mantenimiento</option>
        </select>
        @include('partials.field-error', ['field' => 'service_type'])
    </div>

    <div class="field">
        <label for="priority">Prioridad *</label>
        <select id="priority" name="priority" required aria-invalid="{{ $errors->has('priority') ? 'true' : 'false' }}">
            @foreach($priorities as $priority)
                <option value="{{ $priority->value }}" @selected(old('priority', $order->priority?->value ?? 'medium') === $priority->value)>{{ $priority->etiqueta() }}</option>
            @endforeach
        </select>
        @include('partials.field-error', ['field' => 'priority'])
    </div>

    <div class="field">
        <label for="estimated_cost">Costo estimado</label>
        <input id="estimated_cost" type="number" name="estimated_cost" min="0" max="99999999.99" step="0.01" value="{{ old('estimated_cost', $order->estimated_cost ?? '') }}" placeholder="0.00" aria-invalid="{{ $errors->has('estimated_cost') ? 'true' : 'false' }}">
        @include('partials.field-error', ['field' => 'estimated_cost'])
    </div>

    <div class="field full">
        <label for="reported_problem">Problema reportado *</label>
        <textarea id="reported_problem" name="reported_problem" required maxlength="5000" placeholder="Describe la falla o el mantenimiento solicitado" aria-invalid="{{ $errors->has('reported_problem') ? 'true' : 'false' }}">{{ old('reported_problem', $order->reported_problem ?? '') }}</textarea>
        @include('partials.field-error', ['field' => 'reported_problem'])
    </div>
</div>

<p class="note" style="margin-top: 1rem;">El costo final se registrará al guardar la reparación, después del diagnóstico.</p>

<div class="form-actions">
    <a class="button button-secondary" href="{{ $editing ? route('orders.show', $order) : route('orders.index') }}">Cancelar</a>
    <button class="button-primary" type="submit">{{ $editing ? 'Guardar cambios' : 'Crear orden' }}</button>
</div>

<script>
    (() => {
        const client = document.getElementById('client_id');
        const equipment = document.getElementById('equipment_id');
        if (!client || !equipment) return;

        const syncEquipment = () => {
            const clientId = client.value;
            let selectedVisible = false;

            Array.from(equipment.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    option.disabled = true;
                    return;
                }

                const visible = option.dataset.clientId === clientId;
                option.hidden = !visible;
                option.disabled = !visible;
                if (visible && option.selected) selectedVisible = true;
            });

            if (!selectedVisible) equipment.value = '';
            equipment.disabled = clientId === '';
        };

        client.addEventListener('change', syncEquipment);
        syncEquipment();
    })();
</script>
