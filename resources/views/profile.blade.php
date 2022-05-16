<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <x-errors-bag />
    <form action="{{ route("profile.update") }}" method="POST">
        @csrf
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <label for="name" class="form-label">Name:</label>
                    <br>
                    <input type="text" class="form-input" name="name" value="{{Auth::user()->name}}">
                </div>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-auto">
                    <label for="email" class="form-label">Email:</label>
                    <br>
                    <input type="email" class="form-input" name="email" value="{{Auth::user()->email}}">
                </div>
            </div>
            <div class="row mt-1 d-flex justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-dark bg-dark" type="submit">Save</button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>