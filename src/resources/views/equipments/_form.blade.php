<div class="form-grid">
    <div class="field full"><label for="client_id">Cliente *</label><select id="client_id" name="client_id" required><option value="">Selecciona un cliente</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected((string) old('client_id', $equipment->client_id ?? '') === (string) $client->id)>{{ $client->client_code }} · {{ $client->name }}</option>@endforeach</select></div>
    <div class="field"><label for="equipment_type">Tipo de equipo *</label><input id="equipment_type" name="equipment_type" value="{{ old('equipment_type', $equipment->equipment_type ?? '') }}" placeholder="Laptop, consola, control..." required></div>
    <div class="field"><label for="brand">Marca *</label><input id="brand" name="brand" value="{{ old('brand', $equipment->brand ?? '') }}" required></div>
    <div class="field"><label for="model">Modelo</label><input id="model" name="model" value="{{ old('model', $equipment->model ?? '') }}"></div>
    <div class="field"><label for="serial_number">Número de serie</label><input id="serial_number" name="serial_number" value="{{ old('serial_number', $equipment->serial_number ?? '') }}"></div>
    <div class="field full"><label for="description">Descripción</label><textarea id="description" name="description" maxlength="2000" style="min-height: 100px;">{{ old('description', $equipment->description ?? '') }}</textarea></div>
</div>
<div class="form-actions"><a class="button button-secondary" href="{{ route('equipments.index') }}">Cancelar</a><button class="button-primary" type="submit">{{ isset($equipment) ? 'Guardar cambios' : 'Registrar equipo' }}</button></div>
