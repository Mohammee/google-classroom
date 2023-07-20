{{--<x-form-input>--}}
{{--    <x-slot:lable>--}}
{{--        <label for="exampleFormControlInput1" class="form-label">Name</label>--}}
{{--    </x-slot:lable>--}}

{{--    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="exampleFormControlInput1" value="{{ old('name', $classroom->name) }}">--}}

{{--</x-form-input>--}}


<x-form.input-group name="name">
    <x-slot:label>
        <x-form.label name="name" label="Name" />
    </x-slot:label>
    <x-form.input name="name" :value="$classroom->name" placeholder="Classroom name" class="fs-5"/>
</x-form.input-group>

<x-form.input-group name="section">
    <x-form.input name="section" :value="$classroom->section" placeholder="Classroom section"/>
    <x-slot:label>
        <x-form.label name="section" label="Section" />
    </x-slot:label>
</x-form.input-group>

<x-form.input-group name="subject" >
    <x-form.input name="subject" :value="$classroom->subject" placeholder="Classroom subject"/>
    <x-slot:label>
        <x-form.label name="subject" label="Subject" />
    </x-slot:label>
</x-form.input-group>

<x-form.input-group name="room" >
    <x-form.input name="room" :value="$classroom->room" placeholder="Classroom room"/>
    <x-slot:label>
        <x-form.label name="section" label="Section" />
    </x-slot:label>
</x-form.input-group>

<x-form.input-group name="code" >
    <x-form.input name="code" :value="$classroom->code" placeholder="Classroom code"/>
    <x-slot:label>
        <x-form.label name="code" label="Code" />
    </x-slot:label>
</x-form.input-group>

<x-form.input-group name="image" >
    <div class="row">
        <div @class(['col-md-10' => $classroom->imagePath])>
            <x-form.input type="file" name="image"/>
        </div>

        @if($classroom->imagePath)
            <img class="col-md-2" style="width: 150px;height: 50px; object-fit: cover;"
                 src="{{ $classroom->imagePath }}" alt="image">
        @endif
    </div>

    <x-slot:label>
        <x-form.label name="image" label="Image" />
    </x-slot:label>
</x-form.input-group>

{{--<div class="mb-3">--}}
{{--    <label for="exampleFormControlInput1" class="form-label">Section</label>--}}
{{--    <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" id="exampleFormControlInput1" value="{{ old('section', $classroom->section) }}">--}}
{{--    <x-error name="section" />--}}

{{--</div>--}}

{{--<div class="mb-3">--}}
{{--    <label for="exampleFormControlInput1" class="form-label">Subject</label>--}}
{{--    <input type="text" name="subject" @class(['form-control', 'is-invalid' => $errors->has('subject')])  id="exampleFormControlInput1" value="{{ old('subject', $classroom->subject) }}">--}}
{{--    <x-error name="subject" />--}}

{{--</div>--}}

{{--<div class="mb-3">--}}
{{--    <label for="exampleFormControlInput1" class="form-label">Room</label>--}}
{{--    <input type="text"  name="room" @class(['form-control', 'is-invalid' => $errors->has('room')]) id="exampleFormControlInput1" value="{{ old('room', $classroom->room) }}">--}}
{{--    <x-error name="room" />--}}

{{--</div>--}}

{{--<div class="mb-3">--}}
{{--    <label for="exampleFormControlInput1" class="form-label">Code</label>--}}
{{--    <input type="text"  name="code" @class(['form-control', 'is-invalid' => $errors->has('code')]) id="exampleFormControlInput1" value="{{ old('code', $classroom->code) }}">--}}
{{--    <x-error name="code" />--}}

{{--</div>--}}

{{--<div class="mb-3">--}}
{{--    <label for="exampleFormControlInput1" class="form-label">Image</label>--}}
{{--    <div class="row">--}}
{{--        <div @class(['col-md-10' => $classroom->imagePath])>--}}
{{--            <input type="file"  name="image" @class(['form-control', 'is-invalid' => $errors->has('image')]) id="exampleFormControlInput1" >--}}
{{--        </div>--}}

{{--        @if($classroom->imagePath) <img  class="col-md-2" style="width: 150px;height: 50px; object-fit: cover;" src="{{ $classroom->imagePath }}" alt="image"> @endif--}}
{{--    </div>--}}
{{--    <x-error name="image" />--}}

{{--</div>--}}

<button class="btn btn-primary p-10 mb-10">{{ $buttonLabel }}</button>
