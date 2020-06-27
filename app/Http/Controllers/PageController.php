<?php

namespace App\Http\Controllers;

use App\Page;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(!Helper::checkAccess("Page","View")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $s = request()->s ?? "";
        $datas = Page::where('title','LIKE','%'.$s.'%')->paginate(10);
        return view('admin.page.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if(!Helper::checkAccess("Page","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $cats = Page::pluck('title','id');
        return view('admin.page.create',compact('cats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if(!Helper::checkAccess("Page","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'title'=>'required|string|max:191',
            'content'=>'required|strin',
            'slug'=>'required|string'
        ]);
        $page = Page::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'slug'=>Helper::makeSlug($request->slug,Page::select('id')),
            'status'=>$request->status,
            'user_id'=>Auth::user()->id
        ]);
        
        Helper::addUserLog("Add new page with title : ".$page->title);
        return redirect(route('admin.page.index'))->with(['success'=>"Add New Page with title ".$page->title]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {   
        if(!Helper::checkAccess("Page","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $data = $page;
        return view('admin.page.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {   
        if(!Helper::checkAccess("Page","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'title'=>'required|string|max:191',
            'content'=>'required|string',
            'slug'=>'required|string'
        ]);
    
        $slug = $request->slug;
        if($request->slug != $page->slug){
            $slug = Helper::makeSlug($request->slug,Page::select('id'));
        }
        $page->update([
            'title'=>$request->title,
            'content'=>$request->content,
            'slug'=>$slug,
            'status'=>$request->status
        ]);

        Helper::addUserLog("Update page with title : ".$page->title);

        return redirect(route('admin.page.index'))->with(['success'=>"Update Page with title ".$page->title]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {   
        if(!Helper::checkAccess("Page","Delete")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $page->delete();
        Helper::addUserLog("Delete page with title : ".$page->title);
        return redirect(route('admin.page.index'))->with(['success'=>"Delete Page with title ".$page->title]);
    }


    //web-builder
    public function viewWebBuilder()
    {
        return view('admin.page.web-builder',['action'=>'Add']);
    }

    //edit web builder
    public function editWebBuilder($id)
    {
        $page = Page::find($id);
        return view('admin.page.web-builder',['action'=>'Edit'],compact('page'));
    }

    //load web builder
    public function loadWebBuilder($id)
    {
        $page = Page::find($id);
        return json_encode(['gjs-html'=>$page->content ?? null]);
    }


    //save web builder
    public function saveWebBuilder(Request $request)
    {   
        $page = Page::create([
            'title'=>$request->title,
            'content'=>$request['gjs-html'],
            'slug'=>Helper::makeSlug($request->title,Page::select('id')),
            'status'=>$request->status,
            'type'=>"Builder",
            'caption'=>$request->caption,
            'user_id'=>Auth::user()->id
        ]);
        Helper::addUserLog("Add new page with title : ".$page->title);
        // $fp = fopen('web-builder.html', 'w');
        // fwrite($fp, $request['gjs-html']);
        // fclose($fp);
        if($page){
            return ['status'=>200];
        } else {
            return ['status'=>500];
        }
    }

    //update web builder
    public function updateWebBuilder(Request $request,$id)
    {   

        $page = Page::find($id);
        $slug = Helper::makeSlug($request->title,Page::select('id'));
        if($slug != $page->slug){
            $slug = Helper::makeSlug($request->title,Page::select('id'));
        }

       
        $page->update([
            'title'=>$request->title,
            'content'=>$request['gjs-html'],
            'slug'=>$slug,
            'status'=>$request->status,
            'type'=>"Builder",
            'caption'=>$request->caption
        ]);
        Helper::addUserLog("Update page with title : ".$page->title);
        // $fp = fopen('web-builder.html', 'w');
        // fwrite($fp, $request['gjs-html']);
        // fclose($fp);
        if($page){
            return ['status'=>200];
        } else {
            return ['status'=>500];
        }
    }
}
