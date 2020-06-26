<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(!Helper::checkAccess("Post","View")){
           return redirect('/home')->with(['error'=>"You dont have permission"]);
        }
        $s = request()->s ?? "";
        $datas = Post::where('title','LIKE','%'.$s.'%')->orderBy('created_at','DESC')->paginate(10);
        $lastID = 1;
        $ld = Post::orderBy('id','DESC')->withTrashed()->first();
        if($ld){
            $lastID = $ld->id+1;
        }
        return view('admin.post.index',compact('datas','lastID'));
    }

    public function makeSub($parents,$post = null)
    {   
        $subs = [];
        foreach ($parents as $parent) {
            $subs[] = [
                'name'=>$parent->name,
                'subs'=>(is_array($parent->SubCategories) && count($parent->SubCategories) > 0)?[]:$this->makeSub($parent->SubCategories),
                'checked'=>($post)?(false !== array_search($parent->name,array_map(function($v){return $v['name'];},$post->Categories()->get()->toArray()))):false
            ];
        }

        return $subs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if(!Helper::checkAccess("Post","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $categories = [];
        $cat = Category::all();
        foreach ($cat as $value) {
            $categories[] = [
                'name'=>$value->name,
                'subs'=>$value->SubCategories,
                'checked'=>false
            ];
        }
        $tags = Tag::pluck('name');
        $fullscreen = true;
       
    
        return view('admin.post.create',compact('categories','tags','fullscreen'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if(!Helper::checkAccess("Post","Create")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'title'=>'required|string|max:191',
            'content'=>'required|string',
            'slug'=>'required|string'
        ]);
        $post = Post::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'slug'=>Helper::makeSlug($request->slug,Post::select('id')),
            'featured_image'=>$request->featured_image,
            'status'=>$request->status,
            'user_id'=>Auth::user()->id
        ]);
        
        $tag_id = [];
        foreach ($request->tag as $tag) {
            $cek = Tag::where('name',$tag)->first();
            if(!$cek){
                $cek = Tag::create([
                    'name'=>$tag,
                    'slug'=>Helper::makeSlug($tag,Tag::select('id'))
                ]);
            }
            
            $tag_id[] = $cek->id;
        }
        $post->Tags()->sync($tag_id);

        $category_id = [];
        foreach ($request->category as $category) {
            $cek = Category::where('name',$category)->first();
            if(!$cek){
                $cek = Category::create([
                    'name'=>$category,
                    'slug'=>Helper::makeSlug($category,Category::select('id'))
                ]);
            }
            $category_id[] = $cek->id;
        }
        $post->Categories()->sync($category_id);

        Helper::addUserLog("Add new post with title : ".$post->title);
        
        return redirect(route('admin.post.index'))->with(['success'=>"Add new post with title : ".$post->title]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {    
        if(!Helper::checkAccess("Post","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $categories = [];
        $cat = Category::all();
        foreach ($cat as $value) {
            $categories[] = [
                'name'=>$value->name,
                'subs'=>$value->SubCategories,
                'checked'=>(false !== array_search($value->name,array_map(function($v){return $v['name'];},$post->Categories()->get()->toArray())))
            ];
        }
        $tags = Tag::pluck('name');
        $fullscreen = true;
        return view('admin.post.edit',compact('categories','tags','post','fullscreen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {   
        if(!Helper::checkAccess("Post","Update")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $request->validate([
            'title'=>'required|string|max:191',
            'content'=>'required|string',
            'slug'=>'required|string'
        ]);
        $slug = $request->slug;
        if($request->slug != $post->slug){
            $slug = Helper::makeSlug($request->slug,Post::select('id'));
        }
        $post->update([
            'title'=>$request->title,
            'content'=>$request->content,
            'slug'=>$slug,
            'featured_image'=>$request->featured_image,
            'status'=>$request->status
        ]);
        
        $tag_id = [];
        foreach ($request->tag as $tag) {
            $d = Tag::firstOrCreate([
                'name'=>$tag
            ]);
            $tag_id[] = $d->id;
        }
        $post->Tags()->sync($tag_id);

        $category_id = [];
        foreach ($request->category as $category) {
            $d = Category::firstOrCreate([
                'name'=>$category
            ]);
            $category_id[] = $d->id;
        }
        $post->Categories()->sync($category_id);
        Helper::addUserLog("Update post with title : ".$post->title);
        return redirect(route('admin.post.index'))->with(['success'=>"Update post with title : ".$post->title]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {   
        if(!Helper::checkAccess("Post","Delete")){
            return redirect('/admin/home')->with(['error'=>"You dont have permission"]);
        }
        $post->Tags()->delete();
        $post->Categories()->delete();
        $post->delete();
        Helper::addUserLog("Delete post with title : ".$post->title);
        return redirect(route('admin.post.index'))->with(['success'=>"Delete post with title : ".$post->title]);

    }
}
