<x-main-layout title="Join Classroom">


    <x-success />
<div class="my-xl-5 d-flex align-items-center justify-content-center vh100">
    <div class="border p-5 text-center align-items-center">
        <h2>{{ $classroom->name . '  (#' . $classroom->id .').' }}</h2>
         <p>{{ $classroom->section }}</p>
        <p><a href="{{ $invitation_link }}" target="_blank">{{ $invitation_link }}</a></p>
        <a href="{{ route('classrooms.classworks.index', $classroom->id) }}">
            <button @class(['btn btn-secondary'])>Classworks</button>
        </a>
    </div>
</div>


</x-main-layout>
