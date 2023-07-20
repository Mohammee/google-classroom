@props(['items'])

@foreach($items as $item)
    <x-card :item="$item" />
@endforeach
