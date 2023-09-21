
@extends('layout.master')

@section('content')
    <x-success />


    <ul id="classrooms">

    </ul>

    <div class="row mt-5">
       <x-foreach :items="$classrooms" />
    </div>
@endsection


@push('extra-js')
    <script>
        fetch('/api/v1/classrooms')
            .then((res => res.json()))
            .then(json => {
                let ul = document.getElementById('classrooms');
                for (let i in json){
                    ul.innerHTML += `<li>${json.date['i'].name}</li>`
                }
            })
    </script>
@endpush
