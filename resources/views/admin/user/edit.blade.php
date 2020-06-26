@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.user.index') }}">User</a>
    </li>
    <li class="breadcrumb-item active">Edit User</li>
@endsection

@section('content')


<form action="{{ route('admin.user.update',$data->id) }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit User</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" required placeholder="Name" value="{{ old('name',@$data->name) }}">
                </div>

                <div class="form-group">
                    <label for="name">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required placeholder="Email" value="{{ old('email',@$data->email) }}">
                </div>

                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" id="role_id" class="form-control">
                        @foreach ($roles as $id => $role)
                            <option value="{{ $id }}" @if ($id == $data->role_id)
                                selected
                            @endif>{{ $role }}</option>
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

