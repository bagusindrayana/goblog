@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item active">Menu</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>
                    Current Menu Used
                </b>
            </div>
            
        </div>
            
        <div class="card-body">
            <form action="{{ (isset($selectedMenu))?route('admin.setting.update',$selectedMenu->id):route('admin.setting.store') }}" method="POST">
                @csrf
                @if (isset($selectedMenu))
                    <input type="hidden" name="_method" value="PUT">
                @endif
                <div class="form-group">
                    <label for="main_menu">Selected Menu</label>
                    <select name="main_menu" id="main_menu" class="form-control">
                        <option value="">Nothing Selected</option>
                        @foreach ($avilabeMenus as $item)
                            <option value="{{ $item->id }}" @if (isset($selectedMenu) && $item->id == $selectedMenu->setting_value)
                                selected
                            @endif>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for=""></label>
                    <button type="submit" class="btn btn-success">Save Selected Menu</button>
                </div>
            </form>
        </div>

        
    </div>

    {!! Menu::render() !!}

    
@endsection
@push('scripts')
    {!! Menu::scripts() !!}
@endpush
