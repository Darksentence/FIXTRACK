<div class="form-grid">
    <div class="field">
        <label for="client_code">Código del cliente *</label>
        <input id="client_code" name="client_code" value="{{ old('client_code', $client->client_code ?? '') }}" maxlength="30" placeholder="C-0002" required aria-invalid="{{ $errors->has('client_code') ? 'true' : 'false' }}">
        @include('partials.field-error', ['field' => 'client_code'])
    </div>
    <div class="field">
        <label for="name">Nombre completo *</label>
        <input id="name" name="name" value="{{ old('name', $client->name ?? '') }}" minlength="2" maxlength="150" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
        @include('partials.field-error', ['field' => 'name'])
    </div>
    <div class="field">
        <label for="phone">Teléfono *</label>
        <input id="phone" type="tel" name="phone" value="{{ old('phone', $client->phone ?? '') }}" maxlength="30" placeholder="7777-1234" pattern="(?=(?:\D*\d){8})[+]?[0-9][0-9\s().-]{6,28}[0-9]" title="Ingresa un teléfono válido con al menos 8 dígitos." required aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}">
        @include('partials.field-error', ['field' => 'phone'])
    </div>
    <div class="field">
        <label for="email">Correo electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email', $client->email ?? '') }}" maxlength="150" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
        @include('partials.field-error', ['field' => 'email'])
    </div>
    <div class="field full">
        <label for="address">Dirección</label>
        <textarea id="address" name="address" maxlength="500" style="min-height: 90px;" aria-invalid="{{ $errors->has('address') ? 'true' : 'false' }}">{{ old('address', $client->address ?? '') }}</textarea>
        @include('partials.field-error', ['field' => 'address'])
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
