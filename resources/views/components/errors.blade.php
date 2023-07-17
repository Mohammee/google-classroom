@if($errors->any())
    <ul {{ $attributes->class(['alert', 'alert-danger']) }}>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
