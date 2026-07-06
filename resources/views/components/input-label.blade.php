{{-- Vista Blade de componente reutilizable de interfaz; renderiza una parte de la interfaz. --}}
@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
