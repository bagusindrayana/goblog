<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{   

    public function welcomePage(Request $request)
    {
        $posts = Helper::postList();
        $title = config('app.name', 'Laravel');
        $subTitle = config('app.description', 'Laravel');
        return view('welcome',compact('posts','title','subTitle'));
    }

    public function singlePost($year,$month,$day,$slug)
    {   
        
        $post = Post::where(DB::raw('DATE(created_at)'),($year."-".$month."-".$day))->where('slug',$slug)->first();
        if(!$post){
            return abort(404);
        }
        return view('single-post',compact('post'));
    }

    public function filterPostByCategory($slug)
    {
        $category = Category::where('slug',$slug)->first();

        $posts = Post::whereHas('Categories',function($q)use($category){
            $q->where('Categories.id',$category->id);
        })->paginate(10);
        $title = "Category : ";
        $subTitle = $category->name;
        return view('welcome',compact('posts','title','subTitle'));

    }

    public function filterPostByTag($slug)
    {
        $tag = Tag::where('slug',$slug)->first();

        $posts = Post::whereHas('Tags',function($q)use($tag){
            $q->where('Tags.id',$tag->id);
        })->paginate(10);
        $title = "Tag : ";
        $subTitle = $tag->name;
        return view('welcome',compact('posts','title','subTitle'));

    }
}
