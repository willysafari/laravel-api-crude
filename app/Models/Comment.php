<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //

    protected $fillable = ['content', 'user_id', 'post_id','status','parent_id'];
}
