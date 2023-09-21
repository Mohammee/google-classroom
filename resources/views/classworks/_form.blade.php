<div class="row">
    <div class="col-md-8">
        <x-form.input-group name="title">
            <x-slot:label>
                <x-form.label name="title" label="Title"/>
            </x-slot:label>
            <x-form.input name="title" class="fs-5" :value="old('title', $classwork->title)"/>

        </x-form.input-group>

        <x-form.input-group name="description">
            <x-slot:label>
                <x-form.label name="description" label="Description (optional)"/>
            </x-slot:label>
            <x-form.textarea name="description" :value="old('description', $classwork->description)" class="fs-5"/>

        </x-form.input-group>

    </div>
    <div class="col-md-4">

        @foreach($classroom->students as $student)
            <div class="form-check">
                <input class="form-check-input" name="students[]" type="checkbox"
                       @checked(!isset($assigned) || in_array($student->id, $assigned ?? []))
                       value="{{ $student->id }}" id="flexCheckDefault-{{ $student->id }}">
                <label class="form-check-label" for="flexCheckDefault-{{ $student->id }}">
                    {{ $student->name }}
                </label>
            </div>
        @endforeach


        @if((($type ?? $classwork->type->value) == 'assignment'))
            <x-form.input-group name="published_at">
                <x-slot:label>
                    <x-form.label name="published_at" label="Published At"/>
                </x-slot:label>
                <x-form.input type="date" name="published_at" :value="$classwork->published_date"/>

            </x-form.input-group>

            <x-form.input-group name="options.grade">
                <x-slot:label>
                    <x-form.label name="grade" label="Grade"/>
                </x-slot:label>
                <x-form.input type="number" name="options[grade]"
                              :value="old('options.grade', $classwork->options['grade'] ?? '')"/>

            </x-form.input-group>


            <x-form.input-group name="options.due">
                <x-slot:label>
                    <x-form.label name="due" label="Due"/>
                </x-slot:label>
                <x-form.input type="date" name="options[due]"
                              :value="old('options.due', $classwork->options['due'] ?? '')"/>

            </x-form.input-group>
        @endif

        <x-form.input-group name="topic_id">
            <x-slot:label>
                <x-form.label name="topic_id" label="Topic (optional)"/>
            </x-slot:label>
            <x-form.select name="topic_id" id="topic_id">
                @foreach($classroom->topics as $topic)
                    <option
                        value="{{ $topic->id }}" @selected($topic->id == $classroom->topic?->id)>{{ $topic->name }}</option>
                @endforeach
            </x-form.select>

        </x-form.input-group>
    </div>
</div>

{{--@push('extra-css')--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">--}}
{{--@endpush--}}

@push('extra-js')
    {{--    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
    {{--    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>--}}

    <script src="https://cdn.tiny.cloud/1/fwmthrzy1mq2bhhmiemh61vazxic1ptbl8jfg041rclk9rz9/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>

    <script>
        // rich text wyciwyg
        tinymce.init({
            selector: '#description',
            plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        });

    </script>
@endpush
