<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AppSetting extends Model
{
    use HasFactory;

    const VENDOR_STORAGE_LIMIT = 'vendor_storage_limit';
    const CANCEL_ORDER_HOURS = 'cancel_order_hours';
    const REFERENCE_NUMBER_PREFIX = 'reference_number_prefix';

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
     * Get multiple settings
     * 
     * @param  array  $keys
     * @return array
     */
    public static function getSettings(array $keys): array
    {
        $appSettings = AppSetting::first();

        if (!$appSettings) {
            return [];
        }
        $appSettingsArray = $appSettings->toArray();

        $settings = [];

        foreach($keys as $key) {
            $settings[$key] = isset($appSettingsArray[$key])
                ? $appSettingsArray[$key] : null;
        }


        return $settings;
    }

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

    /**
     * Get the settings for pay on delivery
     * 
     * @return array
     */
    public static function getPayOnDeliverySettings(): array
    {
        $appSettings = self::first();

        if (!$appSettings) {
            return [];
        }
        $settings = [
            'pay_on_delivery_allowed' => $appSettings->pay_on_delivery_allowed,
            'pay_on_delivery_minimum' => $appSettings->pay_on_delivery_minimum,
            'pay_on_delivery_maximum' => $appSettings->pay_on_delivery_maximum,
        ];

        return $settings;
    }

    /**
     * Get options for review rating
     * 
     * @return array
     */
    public static function getReviewRatingOptions(): array
    {
        return [
            '1' => 'Very Poor',
            '2' => 'Poor',
            '3' => 'Average',
            '4' => 'Good',
            '5' => 'Excellent',
        ];
    }

    /**
     * Default validaton rules
     * 
     * @return array
     */
    public static function getDefaultValidation(): array
    {
        $rules = [
            'hotline' => [
                'string',
                function ($attribute, $value, $fail) {
                    $valid = preg_match('/^\+?\d{8,16}$/', $value);
                    if (!$valid) {
                        $fail('The hotline is invalid.');
                    }
                },
            ],
            'email' => 'email',
            'social_facebook' => 'sometimes|nullable|string|min:5|max:50',
            'social_instagram' => 'sometimes|nullable|string|min:5|max:50',
            'address' => 'sometimes|nullable|string|min:5|max:50',
            'cancel_order_hours' => 'required|numeric|min:1|max:72',
            'vendor_storage_limit' => 'required|numeric|min:1|max:100',
            'reference_number_prefix' => 'required|string|min:2|max:3',
            'pay_on_delivery_allowed' => 'sometimes|nullable|bool',
        ];

        return $rules;
    }
}
