<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'awaiting approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'phone',
        'logo_image_path',
        'is_individual',
        'is_approved',
        'reject_reason',
    ];

    /**
     * The users that belong to the vendor.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relatioship: has one address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'vendor_id');
    }

    /**
     * Relatioship: belongs to many product categories
     */
    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    /**
     * Set name attribute handler
     * 
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get list of roles
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getApprovalStatuses(bool $asKeys = false): array
    {
        $types = [
            self::STATUS_PENDING => ucwords(self::STATUS_PENDING),
            self::STATUS_APPROVED => ucwords(self::STATUS_APPROVED),
            self::STATUS_DISAPPROVED => ucwords(self::STATUS_DISAPPROVED),
        ];

        return $asKeys ? $types : array_keys($types);
    }

    /**
     * Determine if vendor is disapproved
     * 
     * @return bool
     */
    public function getIsDisapprovedAttribute()
    {
        return $this->approval_status == self::STATUS_DISAPPROVED;
    }

    /**
     * Determine if vendor is approved
     * 
     * @return bool
     */
    public function getIsApprovedAttribute()
    {
        return $this->approval_status == self::STATUS_APPROVED;
    }
}
