<x-main-layout :title="__('Classworks')">

    <div class="container">

{{--        <h2>{{ $classroom->title }}</h2>--}}

{{--        {!!  __('<h1> Test </h1>') !!}--}}
{{--        @lang('<h1> Test </h1>')--}}
{{--{{ __('View') }}--}}


        <h3> {{ __('Classworks') }}
{{--            @if(Gate::allows('classworks.create', [$classroom])) --}}
{{--@can('create', ['App\Models\Classwork', $classroom])--}}
@can('classworks.create', [$classroom])
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        + Create
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create', [
    $classroom->id, 'type' => 'assignment']) }}">Assignment</a></li>
                        <li><a class="dropdown-item"
                               href="{{ route('classrooms.classworks.create', [
    $classroom->id, 'type' => 'material']) }}">Material</a></li>
                        <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create', [
    $classroom->id, 'type' => 'question']) }}">Question</a></li>
                    </ul>
                </div>
    @endcan
{{--            @endif--}}
        </h3>


        <form action="{{ \Illuminate\Support\Facades\URL::current() }}" method="get">

            <div class="row">
                <div class="col-md-4">
                    <input name="search" type="text" class="form-control" value="{{ request()->query('search') }}"
                           placeholder="Search ...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary ms-2" type="submit">Search</button>
                </div>
            </div>
        </form>

        {{--        @forelse($classworks as $group)--}}

        {{--            <h3>{{ $group->first()->topic?->name }}</h3>--}}
        <div class="accordion accordion-flush" id="accordionFlushExample">
            @foreach($classworks as $classwork)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse-{{ $classwork->id }}" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                            {{ $classwork->title }}
                        </button>
                    </h2>
                    <div id="flush-collapse-{{ $classwork->id }}" class="accordion-collapse collapse"
                         aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ $classwork->description }}

                                </div>
                                <div class="col-md-6 row">
                                    <div class="col-md-4">
                                      <div class="fs-3">
                                          {{ $classwork->assigned_count }}
                                      </div>
                                        <div class="text-muted">{{ __('Assigned') }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="fs-3">
                                            {{ $classwork->turnedin_count }}
                                        </div>
                                        <div class="text-muted">{{ __('Turned In') }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="fs-3">
                                            {{ $classwork->graded_count }}
                                        </div>
                                        <div class="text-muted">{{ __('Grade In') }}</div>
                                    </div>

                                </div>
                            </div>

                            <div class="d-flex justify-content-around my-1 px-2">
                                <a class="btn btn-primary"
                                   href="{{ route('classrooms.classworks.edit', [$classroom->id, $classwork->id]) }}">{{ __('Edit') }}</a>
                                <a class="btn btn-primary"
                                   href="{{ route('classrooms.classworks.show', [$classroom->id, $classwork->id]) }}">{{ __('Show') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        {{--        @empty--}}
        {{--            <p class="text-center text-green-600 fs-5">No Classworks to show</p>--}}
        {{--            @endforelse--}}

        <div>
            {{ $classworks->withQueryString()->appends(['x' => 2])->links() }}
        </div>
    </div>

    @push('extra-js')
        <script>
            const classroomId = '{{ $classroom->id }}';
        </script>

    @endpush

</x-main-layout>
