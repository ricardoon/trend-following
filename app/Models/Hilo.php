<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hilo extends Model
{
    use HasFactory;

    protected $fillable = [
        'length', 'last_check'
    ];

    public function asset()
    {
        return $this->belongsToOne(Asset::class);
    }
}
