<x-main-layout title="Create Classwork">

    <div class="container">

        <x-errors />
        <x-alert name="error" class="alert-danger" />

        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>Create Classwork</h3>
        <hr>
    <form action="{{ route('classrooms.classworks.store', [$classroom->id, 'type' => $type]) }}" method="post" class="form my-5">
        @csrf


     @include('classworks._form')


        <button class="btn btn-primary" type="submit">Create</button>

    </form>
    </div>

</x-main-layout>
