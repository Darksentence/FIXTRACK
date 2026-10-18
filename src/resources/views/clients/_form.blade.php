<div class="form-grid">
    <div class="field">
        <label for="client_code">Código del cliente *</label>
        <input id="client_code" name="client_code" value="{{ old('client_code', $client->client_code ?? '') }}" maxlength="30" placeholder="C-0002" required>
    </div>
    <div class="field">
        <label for="name">Nombre completo *</label>
        <input id="name" name="name" value="{{ old('name', $client->name ?? '') }}" maxlength="150" required>
    </div>
    <div class="field">
        <label for="phone">Teléfono *</label>
        <input id="phone" name="phone" value="{{ old('phone', $client->phone ?? '') }}" maxlength="30" required>
    </div>
    <div class="field">
        <label for="email">Correo electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email', $client->email ?? '') }}" maxlength="150">
    </div>
    <div class="field full">
        <label for="address">Dirección</label>
        <textarea id="address" name="address" maxlength="500" style="min-height: 90px;">{{ old('address', $client->address ?? '') }}</textarea>
    </div>
    <label style="display: flex; align-items: center; gap: .5rem;">
        <input style="width: auto;" type="checkbox" name="active" value="1" @checked(old('active', $client->active ?? true))>
        Cliente activo
    </label>
</div>
<div class="form-actions">
    <a class="button button-secondary" href="{{ route('clients.index') }}">Cancelar</a>
    <button class="button-primary" type="submit">{{ isset($client) ? 'Guardar cambios' : 'Crear cliente' }}</button>
</div>
