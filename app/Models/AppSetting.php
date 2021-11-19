<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    const VENDOR_STORAGE_LIMIT = 'vendor_storage_limit';
    const CANCEL_ORDER_HOURS = 'cancel_order_hours';

    /**
     * Mass assignable fields
     * 
     * @var  array
     */
    protected $fillable = [
        'hotline',
        'email',
        'address',
        self::VENDOR_STORAGE_LIMIT,
        self::CANCEL_ORDER_HOURS,
        'social_facebook',
        'social_twitter',
        'social_youtube',
        'social_linkedin',
        'social_instagram',
        'social_whatsapp',
    ];

    /**
     * Get specific setting
     * 
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function getSetting(string  $key, $default = null)
    {
        $appSettings = AppSetting::first();

        if (!$appSettings) {
            return $default;
        }

        $appSettingsArray = $appSettings->toArray();

        return isset($appSettingsArray[$key])
            ? $appSettingsArray[$key]
            : $default;
    }
}
