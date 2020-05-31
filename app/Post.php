<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use VanOns\Laraberg\Models\Gutenbergable;

class Post extends Model
{   
    use Gutenbergable,SoftDeletes;
    protected $guarded = [];


    public function Categories()
    {
        return $this->morphToMany(Category::class,'categoryable');
    }

    public function Tags()
    {
        return $this->morphToMany(Tag::class,'taggable');
    }

    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    //attribute
    public function getMinContentAttribute()
    {
        return mb_strimwidth($this->content,0,200,"...");
    }

    public function getLinkAttribute()
    {
        return $this->created_at->format('Y/m/d/').$this->slug;
    }
}
