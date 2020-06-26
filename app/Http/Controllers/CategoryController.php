<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(!Helper::checkAccess("Category","View")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $s = request()->s ?? "";
        $datas = Category::where('name','LIKE','%'.$s.'%')->paginate(10);
        return view('admin.category.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if(!Helper::checkAccess("Category","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $cats = Category::pluck('name','id');
        return view('admin.category.create',compact('cats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if(!Helper::checkAccess("Category","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'name'=>'required|string|max:100|min:1'
        ]);
        $data = [
            'name'=>$request->name,
            'slug'=>Helper::makeSlug($request->name,Category::select('id'))
        ];
        if($request->parent_id){
            $data['parent_id'] = $request->parent_id;
        }
        $category = Category::create($data);

        Helper::addUserLog("Add new category with name : ".$category->name);

        return redirect(route('admin.category.index'))->with(['success'=>"Add New Category with name ".$category->name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {   
        if(!Helper::checkAccess("Category","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $data = $category;
        return view('admin.category.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {   
        if(!Helper::checkAccess("Category","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'name'=>'required|string|max:100|min:1'
        ]);

        $category->update([
            'name'=>$request->name
        ]);
        Helper::addUserLog("Update category with name : ".$category->name);

        return redirect(route('admin.category.index'))->with(['success'=>"Update Category with name ".$category->name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {   
        if(!Helper::checkAccess("Category","Delete")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $category->delete();
        Helper::addUserLog("Delete category with name : ".$category->name);
        return redirect(route('admin.category.index'))->with(['success'=>"Delete Category with name ".$category->name]);
    }
}
