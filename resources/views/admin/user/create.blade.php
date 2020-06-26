@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.user.index') }}">User</a>
    </li>
    <li class="breadcrumb-item active">Add User</li>
@endsection

@section('content')


<form action="{{ route('admin.user.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add User</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" required placeholder="Name" value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="name">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required placeholder="Email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="name">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required placeholder="Password" value="{{ old('password') }}">
                </div>

                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" id="role_id" class="form-control">
                        @foreach ($roles as $id => $role)
                            <option value="{{ $id }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
              

                <div class="form-group">
                    <button class="btn btn-success" name="status" value="Publish">
                        Save
                    </button>
                </div>
        </div>
    </div>
</form>
@endsection

