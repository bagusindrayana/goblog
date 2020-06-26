@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.role.index') }}">Role</a>
    </li>
    <li class="breadcrumb-item active">Add Role</li>
@endsection

@section('content')


<form action="{{ route('admin.role.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add Role</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="role_name">Name</label>
                    <input type="text" class="form-control" name="role_name" id="role_name" required placeholder="Role Name" value="{{ old('role_name') }}">
                </div>

                <div class="form-group">
                    <label for="table">Role Access</label>
                    <table width="100%" class="table" id="table">

                        <thead>

                            <tr>

                                <th colspan="6">Access</th>

                                <th>Select All</th>

                            </tr>

                        </thead>

                        <tbody>
                            <?php $no_plh=1; ?>
                        <tr>
                            <td colspan="7" align="right"><input type="checkbox" id="{{ $no_plh }}"></td>
                        </tr>
                        <tr>

                            <?php 
                                $access_name = "";
                               
                                
                                foreach ($access as $m): 
                                    $no_plh++;
                                    
                                    if($access_name != $m->access_name):
                                    ?>
                                    <tr>
                                        
                                    <?php
                                    $col = 7;
                                    foreach ($access as $d): 
                                        if($m->access_name == $d->access_name  ){
                                            
                                            $col--;
                                            ?>
                                            <td  width="16%"><input  class="sel s{{ $no_plh }}"  type="checkbox" name="access[]" value="{{ $d->id }}"> {{ $d->access_name }} {{ $d->access_action }}</td>

                                            <?php
                                        }
                                    endforeach;
                                    ?>
                                        <td colspan="{{ $col }}" align="right"><input class="sel pilih" nomornya="{{ $no_plh }}" type="checkbox" ></td>
                                    </tr>
                                    <?php
                                    
                                    endif;
                                    $access_name = $m->access_name;
                                endforeach; 
                            ?>

                                

                            </tr>

                        </tbody>

                    </table>
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

@push('scripts')
<script type="text/javascript">
    $("#1").click(function () {
        $('.sel').not(this).prop('checked', this.checked);
    });
    $(".pilih").click(function () {
        $(".s"+$(this).attr('nomornya')).not(this).prop('checked', this.checked);
    });
</script>
@endpush

