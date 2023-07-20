
@extends('layout.master')

@section('content')
    <x-success />

    <div class="row mt-5">
       <x-foreach :items="$classrooms" />
    </div>
@endsection
