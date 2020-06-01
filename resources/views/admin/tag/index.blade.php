@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item active">Tag</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="float-left">
            <b>
                Tag
            </b>
        </div>
        <div class="float-right">
            <a href="{{ route('admin.tag.create') }}" class="btn btn-primary">
                Add Tag
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
                            <form action="{{ route('admin.tag.destroy',$data->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="{{ route('admin.tag.edit',$data->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-danger" type="submit">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        {!! $datas->links() !!}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
