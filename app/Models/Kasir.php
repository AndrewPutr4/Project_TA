<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Kasir extends Authenticatable
{
    protected $guard = 'kasir';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
