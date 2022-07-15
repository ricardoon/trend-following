<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'general',
        'binance',
    ];

    protected $casts = [
        'general' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function binance(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $value = json_decode($value, true);
                if (is_array($value)) {
                    try {
                        $value['api_key'] = Crypt::decryptString($value['api_key']);
                        $value['api_secret'] = Crypt::decryptString($value['api_secret']);
                    } catch (DecryptException $e) {
                        return null;
                    }
                    return $value;
                }
                return null;
            },
            set: fn ($value) => json_encode([
                'api_key' => Crypt::encryptString($value['api_key']),
                'api_secret' => Crypt::encryptString($value['api_secret']),
            ]),
        );
    }
}
