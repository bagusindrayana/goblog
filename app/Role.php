<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function access()
    {
        return $this->belongsToMany(Access::class,'role_accesses');
    }
}
