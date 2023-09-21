<x-main-layout title="Create classwork">
    <div class="container">

        <x-errors/>
        <x-alert name="error" class="alert-danger" />
        <x-success/>
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>{{ $classwork->title }}</h3>
        <hr>

        <div class="row">
            <div class="col-md-8">
                <div>
{{--                    rich editor make scape for script tage--}}
{{--                    laravel  unscape--}}
                    {!! $classwork->description !!}
                </div>
                <h4>Comments</h4>
                <form action="{{ route('comments.store') }}" method="post">
                    @csrf

                    <input type="hidden" name="id" value="{{ $classwork->id }}">
                    <input type="hidden" name="type" value="classwork">
                    <div class="d-flex">
                        <div class="col-8">

                            <x-form.input-group name="content">
                                <x-form.textarea name="content" :value="old('content')"/>
                            </x-form.input-group>

                        </div>
                        <div class="ms-1">
                            <button type="submit" class="btn btn-primary">Comment</button>

                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="bordered rounded p-3 bg-light">
                    <h3>Submissions</h3>

                    @if($submissions->count())

                        <ul>
                            @foreach($submissions as $submission)
                                <li>
                                    <a href="{{ route('submissions.show', $submission->id) }}" target="_blank">File #{{ $loop->iteration }}</a>
                                </li>
                            @endforeach
                        </ul>

                    @else
                        @can('submissions.create', [$classwork])
                        <form action="{{ route('submissions.store', $classwork->id) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <x-form.input-group name="file.0">
                                <x-form.input type="file" name="files[]" multiple/>
                            </x-form.input-group>

                            <button class="btn bnt-primary" type="submit">Submit</button>

                        </form>
                        @endcan
                    @endif

                </div>
            </div>
        </div>

        <div class="mt-4">

            @foreach ($classwork->comments()->latest()->with('user')->get() as $comment)
                <div class="row">
                    <div class="col-md-2">
                        <img src="https://ui-avatars.com/api/?name=mohammad+Abo+Sultan" alt="" width="100"
                             style="object-fit: cover;">
                    </div>
                    <div class="col-md-10">
                        <p>By: {{ $comment->user->name }} .
                            Time: {{ $comment->created_at->diffforHumans(null, true, true) }}</p>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</x-main-layout>
