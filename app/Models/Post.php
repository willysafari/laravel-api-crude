<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable = ['title', 'content', 'slug', 'created_at', 'updated_at', 
    'excerpt', 'status', 'thumbnail', 'user_id', 'blog_category_id','published_at'];
}
