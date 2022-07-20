<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hilo extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id', 'length', 'granularity',
        'last_check_at', 'last_action',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
