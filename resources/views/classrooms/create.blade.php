<x-main-layout title="Create Item">

  <x-errors />
    <x-alert name="error" class="alert alert-danger" />
    <form action="{{ url('/classrooms') }}" method="post" class="form" enctype="multipart/form-data">

       <!-- <input type="hidden" value="{{ csrf_token() }}">
        {{ csrf_field() }}
        -->
{{--        {{ csrf_field() }}--}}

        @csrf

        <!-- Form method spoofing -->
{{--        @method('PUT')--}}

        @include('classrooms._form', ['buttonLabel' => 'Create Room'])


    </form>
</x-main-layout>
