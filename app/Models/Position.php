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
        'asset_id', 'user_id', 'strategy', 'granularity',
        'initial_amount', 'leverage', 'amount',
        'started_at', 'ended_at', 'max_stop'
    ];

    public function scopeActive($query)
    {
        $query->where('ended_at', null);
    }

    public function scopeInactive($query)
    {
        $query->where('ended_at', '!=', null);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}
