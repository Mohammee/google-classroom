@props(['name', 'value' => '', 'id' => null])

<textarea name="{{ $name }}"
          id="{{ $id ?? $name }}"
    {{ $attributes->merge([])->class(['form-control',
'is-invalid' => $errors->has($name)]) }}>{{ $value }}</textarea>
