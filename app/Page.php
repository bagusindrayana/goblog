<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

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
