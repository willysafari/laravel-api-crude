<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //fillable fields
    protected $fillable = ['user_id', 'post_id', 'created_at', 'updated_at'];
}
