<x-main-layout title="Settings">

    <x-errors />
    <x-alert name="error" class="alert alert-danger" />

    <form action="{{ route('settings', $group) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <x-form.input-group name="settings.app_name">
            <x-slot:label>
                <x-form.label name="name" label="Application Name" />
            </x-slot:label>
            <x-form.input name="settings[app.name]" :value="config('app.name')" placeholder="App Name" class="fs-5"/>
        </x-form.input-group>

        <x-form.input-group name="settings.app_logo" >
            <div class="row">
                    <x-form.input type="file" name="settings[app.logo]" class="form-control" />
            </div>

            @if(config('app.logo'))
                <img class="col-md-2" style="width: 150px;height: 50px; object-fit: cover;"
                     src="{{ (\Illuminate\Support\Facades\Storage::disk('public')->url(config('app.logo'))) }}" alt="image">
            @endif

            <x-slot:label>
                <x-form.label name="settings[app_name]" label="App logo" />
            </x-slot:label>
        </x-form.input-group>

        <button class="btn btn-primary p-10 mb-10">Save Settings</button>


    </form>
</x-main-layout>
