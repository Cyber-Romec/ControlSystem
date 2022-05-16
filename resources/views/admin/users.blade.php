<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <x-errors-bag />
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form action="{{ route("admin.user.store") }}" method="POST">
        @csrf
          <div class="container">
              <div class="row">
                  <div class="col-3">
                      <label for="name">Name:</label>
                      <br>
                      <input type="text" name="name" class="form-input" value="{{ old("name") }}">
                  </div>
                <div class="col-3">
                    <label for="email">Email:</label>
                    <br>
                    <input type="email" name="email" class="form-input" value="{{ old("email") }}">
                </div>
                <div class="col-3">
                    <label for="name">Password:</label>
                    <br>
                    <input type="password" name="password" class="form-input">
                </div>
                <div class="col-3">
                    <label for="name">Repeate password:</label>
                    <br>
                    <input type="password" name="repeated_password" class="form-input">
                </div>
            </div>
          </div>
          <div class="row d-flex justify-content-center mt-4">
              <div class="col-auto">
                  <button type="submit" class="btn btn-primary bg-primary">Add User</button>
              </div>
          </div>
    </form>
    <table class="table">
        <tr>
            <th class="scope">Name</th>
            <th class="scope">Email</th>
            <th></th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                    <form action="{{ route("admin.user.delete", $user->id)}}" method="POST">
                        @csrf
                        @method("PATCH")
                        <button type="submit">
                            <img width="25px" height="25px" src="{{ asset("img/delete-64.png")}}" alt="Delete image">
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach    
    </table>
</x-app-layout>