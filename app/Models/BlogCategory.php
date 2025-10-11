<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    //fillable fields
    protected $fillable = ['name', 'slug', 'description', 'created_at', 'updated_at'];
   // --- IGNORE ---
}
