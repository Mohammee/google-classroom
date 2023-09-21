@props([
    'name','type' => 'text', 'value' => '', 'id'
])

@php
$error = str_replace(['[', ']'], ['.',''],$name);

@endphp
<input type="{{ $type }}" name="{{ $name }}"
       id="{{ $id ?? $name }}" value="{{ old($name, $value) }}"
  {{ $attributes
//->merge(['type' => 'text'])
->class(['form-control', 'is-invalid' => $errors->has($error)]) }}>
