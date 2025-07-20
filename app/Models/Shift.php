<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'kasir_id',
        'kasir_name',
        'date',
        'start_time',
        'end_time'
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
