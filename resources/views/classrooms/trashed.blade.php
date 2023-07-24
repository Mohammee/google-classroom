
@extends('layout.master')

@section('content')
    <x-success />

    <div class="row mt-5">
       @foreach($classrooms as $classroom)
            <div class="col-md-4">
                <div class="card my-5">
                    <img src="{{ $classroom->imagePath }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $classroom->name }}</h5>
                        <p class="card-text">{{ $classroom->subject }}</p>

                        <div class="d-flex justify-content-between">
                            <form action="{{ route('classrooms.restore', $classroom->id) }}" method="post">
                                @csrf
                                @method('PUT')

                                <button @class(['btn btn-primary'])>Restore</button>
                            </form>

                            <form action="{{ route('classrooms.force-deleted', $classroom->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button @class(['btn btn-danger'])>Force Delete</button>
                            </form>

                        </div>
                           </div>
                </div>
            </div>

        @endforeach
    </div>
@endsection
