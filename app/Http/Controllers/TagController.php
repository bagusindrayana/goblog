<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(!Helper::checkAccess("Tag","View")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $s = request()->s ?? "";
        $datas = Tag::where('name','LIKE','%'.$s.'%')->paginate(10);
        return view('admin.tag.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if(!Helper::checkAccess("Tag","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        return view('admin.tag.create');
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
            'name'=>'required|string|max:100|min:1'
        ]);
        
        if(!Helper::checkAccess("Tag","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $tag = Tag::create([
            'name'=>$request->name,
            'slug'=>Helper::makeSlug($request->name,Tag::select('id'))
        ]);
        Helper::addUserLog("Add new tag with name : ".$tag->name);
        return redirect(route('admin.tag.index'))->with(['success'=>"Add New Tag with name ".$tag->name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {   
        if(!Helper::checkAccess("Tag","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $data = $tag;
        return view('admin.tag.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {   
        if(!Helper::checkAccess("Tag","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'name'=>'required|string|max:100|min:1'
        ]);

        $tag->update([
            'name'=>$request->name
        ]);

        Helper::addUserLog("Update tag with name : ".$tag->name);

        return redirect(route('admin.tag.index'))->with(['success'=>"Update Tag with name ".$tag->name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {   
        if(!Helper::checkAccess("Tag","Delete")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $tag->delete();
        Helper::addUserLog("Delete tag with name : ".$tag->name);
        return redirect(route('admin.tag.index'))->with(['success'=>"Delete Tag with name ".$tag->name]);
    }
}
