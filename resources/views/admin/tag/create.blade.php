@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.tag.index') }}">Tag</a>
    </li>
    <li class="breadcrumb-item active">Add Tag</li>
@endsection

@section('content')


<form action="{{ route('admin.tag.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add Tag</b>
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
                    <button class="btn btn-success" name="status" value="Publish">
                        Save
                    </button>
                </div>
        </div>
    </div>
</form>
@endsection

