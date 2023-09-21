<x-main-layout title="Classsrooms">
    <div class="container">
        <h1>{{ $classroom->name }} People</h1>

        <x-errors />
        <x-success />
        <table class="table">
            <thead>

            <tr>
                <th></th>
                <th>Name</th>
                <th>Role</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($classroom->users()->orderBy('name')->get() as $user)
                <tr>
                    <td></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->pivot->role }}</td>
                    <td>
                        <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" @class(['btn btn-danger w-100'])>Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-main-layout>
