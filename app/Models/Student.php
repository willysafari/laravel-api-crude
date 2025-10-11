<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //fillable fields
    protected $fillable = [
        'name',
        'email',
        'gender',
    ];
}
