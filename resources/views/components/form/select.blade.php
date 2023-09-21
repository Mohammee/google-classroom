@props(['name'])

<select name="{{ $name }}" id="{{ $id ?? $name }}"
{{ $attributes->merge([])->class(['form-control', 'is_invalid' => $errors->has($name)]) }}>
    {{ $slot }}
</select>
