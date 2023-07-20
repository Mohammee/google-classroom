@props([
    'name' => 'success'
    ])

@if(session()->has($name))
    <div {{ $attributes->class(['alert', 'alert-success']) }} role="alert">
        <strong>{{ session()->get($name) }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
