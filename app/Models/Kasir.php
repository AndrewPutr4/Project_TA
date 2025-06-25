<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Kasir extends Authenticatable
{
    protected $guard = 'kasir';

    protected $fillable = [
        'name', 'email', 'password', 'kasir_id', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
