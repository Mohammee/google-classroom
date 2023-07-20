
<div {{ $attributes->class(['mb-3']) }}>
    {{ $label }}
   {{ $slot }}
    <x-form.error :name="$name"/>
</div>
