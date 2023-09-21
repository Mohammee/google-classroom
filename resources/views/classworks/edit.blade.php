<x-main-layout title="Create Classwork">

    <div class="container">

        <x-errors />

        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>Create Classwork</h3>
        <hr>
    <form action="{{ route('classrooms.classworks.update', [$classroom->id, $classwork->id]) }}" method="post" class="form my-5">
        @csrf
@method('PUT')

     @include('classworks._form')


        <button class="btn btn-primary" type="submit">Update</button>

    </form>
    </div>

</x-main-layout>
