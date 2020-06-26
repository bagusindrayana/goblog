<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use App\Page;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{   

    public function __construct()
    {
        Helper::addVisitor();
    }

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

        $posts = Helper::postList(true,5,request()->s,function($w)use($category){
            $w->whereHas('Categories',function($q)use($category){
                $q->where('Categories.id',$category->id);
            });
        });
        $title = "Category : ";
        $subTitle = $category->name;
        return view('welcome',compact('posts','title','subTitle'));

    }

    public function filterPostByTag($slug)
    {
        $tag = Tag::where('slug',$slug)->first();

        $posts = Helper::postList(true,5,request()->s,function($w)use($tag){
            $w->whereHas('Tags',function($q)use($tag){
                $q->where('Tags.id',$tag->id);
            });
        });
        $title = "Tag : ";
        $subTitle = $tag->name;
        return view('welcome',compact('posts','title','subTitle'));

    }

    public function archivePostYear($year)
    {   
       
        $posts = Helper::postList(true,5,request()->s,function ($w)use($year)
        {
            $w->whereYear('created_at',$year);
        });
        $title = "Year : ";
        $subTitle = $year;
        return view('welcome',compact('posts','title','subTitle'));
    }

    public function archivePostYearMonth($year,$month)
    {
        $posts = Helper::postList(true,5,request()->s,function ($w)use($year,$month)
        {
            $w->whereYear('created_at',$year)->whereMonth('created_at',$month);
        });
        $title = "Year/Month : ";
        $subTitle = $year."/".$month;
        return view('welcome',compact('posts','title','subTitle'));
    }

    public function openPage($slug)
    {
        $page = Page::where('slug',$slug)->first();
        if($page){
            if($page->type == "Default"){
                return view("default-page",compact('page'));
            }
            else if($page->type == "Builder"){
                return view("builder-page",compact('page'));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }
}
