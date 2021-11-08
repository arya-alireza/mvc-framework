<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
    ];

    public function getFullNameAttribute()
    {
        return "$this->fname $this->lname";
    }
}