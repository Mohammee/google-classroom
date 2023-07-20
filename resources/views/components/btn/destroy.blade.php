
@props([
    'name' => 'Delete',
    'id'
])
    <form action="{{ route('classrooms.destroy', $id) }}" method="post">
        @csrf
        @method('delete')
        <button type="submit" {{ $attributes->class(['btn btn-danger']) }}>{{ $name }}</button>
    </form>
