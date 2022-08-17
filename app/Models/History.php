<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'asset_id',
        'date',
        'open',
        'high',
        'low',
        'close',
        'adj_close',
        'volume',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
