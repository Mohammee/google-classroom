<x-main-layout title="Edit Item">

    <x-slot:test>
        <input type="text" name="name" @class(['form-control'])>
    </x-slot:test>
   <x-errors />

<x-alert name="success" is_success="success" id="name" class="alert-succjkess"/>
<x-alert name="error" class="alert-danger"/>
    <form action="{{ route('classrooms.update', $classroom->id) }}" method="post" class="form" enctype="multipart/form-data">

       <!-- <input type="hidden" value="{{ csrf_token() }}">
        {{ csrf_field() }}
        -->
{{--        {{ csrf_field() }}--}}

        @csrf
        @method('PUT')

        <!-- Form method spoofing -->
{{--        @method('PUT')--}}
     @include('classrooms._form', ['buttonLabel' => 'Update Classroom'])

    </form>
</x-main-layout>
