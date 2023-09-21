@props(['item'])
<div class="col-md-4">
    <div class="card my-5">
        <img src="{{ $item->imagePath }}" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">{{ $item->name }}</h5>
            <p class="card-text">{{ $item->subject }}</p>
            <a href="{{ $item->show_url }}" class="btn btn-primary">show</a>
            <a href="{{ route('classrooms.edit', $item->id) }}" class="btn btn-info">edit</a>
            <x-btn.destroy :id="$item->id" />
        </div>
    </div>
</div>
