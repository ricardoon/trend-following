<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'asset_id', 'user_id', 'strategy', 'side',
        'type', 'price', 'start_datetime',
        'end_datetime', 'max_stop'
    ];

    function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
