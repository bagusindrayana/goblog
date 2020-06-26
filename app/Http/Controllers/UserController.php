<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(!Helper::checkAccess("User","View")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $s = request()->s ?? "";
        $datas = User::where('name','LIKE','%'.$s.'%')->paginate(10);
        return view('admin.user.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if(!Helper::checkAccess("User","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $roles = Role::pluck('role_name','id');
        return view('admin.user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if(!Helper::checkAccess("User","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|string|max:100|unique:users',
            'password'=>'required|string|max:100|min:8'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role_id'=>$request->role_id
        ]);

        Helper::addUserLog("Add new user with name : ".$user->name);

        return redirect(route('admin.user.index'))->with(['success'=>"Add New User with name ".$user->name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {   
        if(!Helper::checkAccess("User","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $data = $user;
        $roles = Role::pluck('role_name','id');
        return view('admin.user.edit',compact('data','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {   
        if(!Helper::checkAccess("User","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'name'=>'required|string|max:100|min:1'
        ]);

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'role_id'=>$request->role_id
        ]);

        Helper::addUserLog("Update user with name : ".$user->name);

        return redirect(route('admin.user.index'))->with(['success'=>"Update User with name ".$user->name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {   
        if(!Helper::checkAccess("User","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $user->delete();
        Helper::addUserLog("Delete user with name : ".$user->name);
        return redirect(route('admin.user.index'))->with(['success'=>"Delete User with name ".$user->name]);
    }
}
