
@props([
    'name'
    ])
@if(session()->has($name))

    <div
{{--        class="alert--}}
{{--    alert-{{ $name == 'success' ?$isSuccess ?? 'success': 'danger' }}"--}}
        {{ $attributes->class(['alert', 'alert-success' => $name == 'success'])->merge([
        'id' => 'x'
]) }}>{{ session()->get($name) }}</div>
@endif
