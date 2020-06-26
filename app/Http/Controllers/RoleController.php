<?php
namespace App\Http\Controllers;

use App\Access;
use App\Helpers\Helper;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $s = request()->s ?? "";
        $datas = Role::where('role_name','LIKE','%'.$s.'%')->paginate(10);
        return view('admin.role.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $access = Access::all();
        return view('admin.role.create',compact('access'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name'=>'required|string|max:100|min:1'
        ]);

        $role = Role::create([
            'role_name'=>$request->role_name,
        ]);
        $role->access()->attach($request->access);
        Helper::addUserLog("Add new role with role name : ".$role->role_name);
        return redirect(route('admin.role.index'))->with(['success'=>"Add New Role with role name ".$role->role_name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {   
        $data = $role;
        $access = Access::all();
        $role_access = $role->access()->pluck('access_id')->toArray();
        return view('admin.role.edit',compact('data','access','role_access'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name'=>'required|string|max:100|min:1'
        ]);

        $role->update([
            'role_name'=>$request->role_name
        ]);
        $role->access()->sync($request->access);
        Helper::addUserLog("Update role with role name : ".$role->role_name);

        return redirect(route('admin.role.index'))->with(['success'=>"Update Role with role name ".$role->role_name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {   
        $role->delete();
        Helper::addUserLog("Delete role with role name : ".$role->role_name);
        return redirect(route('admin.role.index'))->with(['success'=>"Delete Role with role name ".$role->role_name]);
    }
}
