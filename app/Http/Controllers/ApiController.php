<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function newCategory(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:100|min:1'
        ]);
        $data = [
            'name'=>$request->name,
            'slug'=>Helper::makeSlug($request->name,Category::select('id'))
        ];
        if($request->parent){
            $parent = Category::where('name',$request->parent)->first();
            if($parent){
                $data['parent_id'] = $parent->id;
            }
        }
        $category = Category::create($data);
        $cat = Category::whereNull('parent_id')->get();
        $categories = $this->makeSub($cat);
        Helper::addUserLog("Add new category with name : ".$category->name);
        return $categories;
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
}
