@extends('admin.layouts.app')
@section('title','User')

@section('breadcrumb')
    <li class="breadcrumb-item active">User</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="float-left">
            <b>
                User
            </b>
        </div>
        <div class="float-right">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                Add User
            </a>
        </div>
    </div>
        
    <div class="card-body">
        
        @include('admin.layouts.includes.search_form')

        <table class="table">
            <thead>
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>
                            {{ $data->name }}
                        </td>
                        <td>
                            {{ $data->email }}
                        </td>
                        <td>
                            <form action="{{ route('admin.user.destroy',$data->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="{{ route('admin.user.edit',$data->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                {{-- make sure you not delete yourself --}}
                                @if (Auth::user()->id != $data->id)
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        Delete
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        {!! $datas->links() !!}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
