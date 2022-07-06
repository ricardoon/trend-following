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
        'position_id', 'side', 'entry_price', 'quantity', 'size', 'exit_price',
        'started_at', 'ended_at', 'binance_client_order_id', 'external_id',
    ];

    protected $casts = [
        'entry_price' => 'float',
        'exit_price' => 'float',
    ];

    public function scopeActive($query)
    {
        $query->where('ended_at', null);
    }

    public function close($data = [])
    {
        $this->ended_at = now();

        if (!empty($data['exit_price'])) {
            $this->exit_price = $data['exit_price'];
        }

        $this->save();

        return null;
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
