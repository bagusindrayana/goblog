@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.category.index') }}">Category</a>
    </li>
    <li class="breadcrumb-item active">Add Category</li>
@endsection
@section('content')


<form action="{{ route('admin.category.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add Category</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            

                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">-- Dont Have --</option>
                        @foreach ($cats as $id => $cat)
                            
                            <option value="{{ $id }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
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

