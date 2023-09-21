<x-main-layout title="Join Classroom">


<div class="my-xl-5 d-flex align-items-center justify-content-center vh100">
    <div class="border p-5 text-center align-items-center">
        <h2>{{ $classroom->name }}</h2>
        <form action="{{ route('classrooms.join', $classroom->id) }}" method="post" class=" ">
            @csrf

            <button type="submit" class="btn btn-primary d-block w-100">Join</button>
        </form>
    </div>
</div>


</x-main-layout>
