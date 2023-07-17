<x-form-input>
{{--    <x-slot:lable>--}}
{{--        <label for="exampleFormControlInput1" class="form-label">Name</label>--}}
{{--    </x-slot:lable>--}}

    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="exampleFormControlInput1" value="{{ old('name', $classroom->name) }}">

</x-form-input>

<div class="mb-3">
    <label for="exampleFormControlInput1" class="form-label">Section</label>
    <input type="tel" name="section" class="form-control @error('section') is-invalid @enderror" id="exampleFormControlInput1" value="{{ old('section', $classroom->section) }}">
    <x-error name="section" />

</div>

<div class="mb-3">
    <label for="exampleFormControlInput1" class="form-label">Subject</label>
    <input type="text" name="subject" @class(['form-control', 'is-invalid' => $errors->has('subject')])  id="exampleFormControlInput1" value="{{ old('subject', $classroom->subject) }}">
    <x-error name="subject" />

</div>

<div class="mb-3">
    <label for="exampleFormControlInput1" class="form-label">Room</label>
    <input type="text"  name="room" @class(['form-control', 'is-invalid' => $errors->has('room')]) id="exampleFormControlInput1" value="{{ old('room', $classroom->room) }}">
    <x-error name="room" />

</div>

<div class="mb-3">
    <label for="exampleFormControlInput1" class="form-label">Code</label>
    <input type="text"  name="code" @class(['form-control', 'is-invalid' => $errors->has('code')]) id="exampleFormControlInput1" value="{{ old('code', $classroom->code) }}">
    <x-error name="code" />

</div>

<div class="mb-3">
    <label for="exampleFormControlInput1" class="form-label">Image</label>
    <input type="file"  name="image" @class(['form-control', 'is-invalid' => $errors->has('image')]) id="exampleFormControlInput1" >
    <x-error name="image" />

</div>

<button class="btn btn-primary p-10 mb-10">{{ $buttonLabel }}</button>
