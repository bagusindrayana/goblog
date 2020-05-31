<?php
namespace App\Helpers;

use App\Category;
use App\Post;
use App\Tag;

class Helper {

    public static function makeSlug($normal,$model){
        $slug = strtolower(str_replace(' ','-',str_replace('/','-',$normal)));
        if($model){
            $cek = $model->where('slug',$slug)->orderBy('created_at','DESC')->first();
            if($cek){
                $slug .= "-".($cek->id+1);
            }
        }
        return $slug;
    }

    public static function categoryList($limit = 0){
        if($limit == 0){
            return Category::orderBy('name','ASC')->pluck('name','slug');
        } else {
            return Category::orderBy('name','ASC')->limit($limit)->pluck('name','slug');
        }
    }

    public static function tagList($limit = 0){
        if($limit == 0){
            return Tag::orderBy('name','ASC')->pluck('name','slug');
        } else {
            return Tag::orderBy('name','ASC')->limit($limit)->pluck('name','slug');
        }
    }

    public static function postList($paginate = true,$limit = 5,$search = null)
    {   
        $s = request()->s ?? ($search ?? "");
        $posts = Post::orderBy('created_at','DESC')->where('title','LIKE','%'.$s.'%');
        
        if($paginate){
            return $posts->paginate($limit);
        } else if($limit > 0){
            return $posts->limit($limit)->get();
        } else {
            return $posts->get();
        }
    }


}