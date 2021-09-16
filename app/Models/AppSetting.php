<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var  array
     */
    protected $fillable = [
        'hotline',
        'email',
        'vendor_storage_limit',
        'social_facebook',
        'social_twitter',
        'social_youtube',
        'social_linkedin',
        'social_instagram',
        'social_whatsapp',
    ];
}
