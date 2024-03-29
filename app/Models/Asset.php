<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'yahoo_code', 'category', 'image',
        'price_precision', 'quantity_precision', 'quote_precision',
    ];

    public function hilos()
    {
        return $this->hasMany(Hilo::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
