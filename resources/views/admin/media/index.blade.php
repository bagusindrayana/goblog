@extends('admin.layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item active">Media</li>
@endsection
@section('content')
<div class="card">
    
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <iframe src="{{ url('filemanager?type=image') }}" style="width:100%;height:70vh;" frameborder="1"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
