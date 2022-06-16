<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'position_id', 'side', 'entry_price', 'started_at',
        'ended_at', 'binance_client_order_id', 'external_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
