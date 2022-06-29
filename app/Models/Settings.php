<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'general',
        'binance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getGeneralAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getBinanceAttribute($value)
    {
        return json_decode($value, true);
    }
}
