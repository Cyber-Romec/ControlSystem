<x-app-layout>
    <x-slot name="header">

    </x-slot>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
    {{-- <div class="row mt-2 mb-3 d-flex justify-content-center">
        <div class="col-auto">
            <h1><b>За смяна на парола</b></h1>
        </div>
    </div>
    <div class="row d-flex justify-content-center mb-3">
        <div class="col-auto">
            <label for="password" class="form-label">password:</label>
            <br>
            <input type="password" class="form-input" name="password">
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-auto">
            <label for="second_password" class="form-label">repeat password:</label>
            <br>
            <input type="password" class="form-input" name="second_password">
        </div>
    </div> --}}
</x-app-layout>