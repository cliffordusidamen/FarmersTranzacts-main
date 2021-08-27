<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'awaiting approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';

    const LOGO_STORAGE_PATH = 'public/vendor-logos';

    const OWNERSHIP_COMPANY = 'company';
    const OWNERSHIP_INDIVIDUAL = 'individual';

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'shop_name',
        'business_name',
        'tin',
        'is_vat_registered',
        'slug',
        'phone',
        'logo_image_path',
        'ownership_type',
        'is_approved',
        'reject_reason',
        'entity_country_id',
        'shipping_country_id',
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
    public function setShopNameAttribute($value)
    {
        $this->attributes['shop_name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get list of statuses
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
     * Get list of ownership types
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getOwnershipTypes(bool $asKeys = false): array
    {
        $types = [
            self::OWNERSHIP_COMPANY => ucwords(self::OWNERSHIP_COMPANY),
            self::OWNERSHIP_INDIVIDUAL => ucwords(self::OWNERSHIP_INDIVIDUAL),
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

    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateLogo(UploadedFile $photo)
    {
        tap($this->logo_image_path, function ($previous) use ($photo) {
            $this->forceFill([
                'logo_image_path' => $photo->storePublicly(
                    self::LOGO_STORAGE_PATH
                ),
            ])->save();

            if ($previous) {
                Storage::disk()->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteLogo()
    {
        if ($this->logo_image_path) {
            Storage::disk()->delete($this->logo_image_path);
        }


        $this->forceFill([
            'logo_image_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_image_path
                    ? Storage::disk()->url($this->logo_image_path)
                    : $this->defaultLogoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultLogoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * filter by name, email and phone
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $searchText
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByText(Builder $query, $searchText): Builder
    {
        $searchText = trim($searchText);

        if (!isset($searchText[2])) {
            return $query;
        }

        return $query->where(function(Builder $q) use ($searchText) {
            $q->where('name', 'LIKE', "%{$searchText}%")
                ->orWhereHas('users', function(Builder $query) use ($searchText) {
                    $query->where(DB::raw('CONCAT(last_name, " ", first_name)'), 'LIKE', "%{$searchText}%")
                        ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "%{$searchText}%")
                        ->orWhere('email', 'LIKE', "%{$searchText}%")
                        ->orWhere('phone', 'LIKE', "%{$searchText}%");
                });
        });

    }

    /**
     * filter by status(awaiting approval only)
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAwaitingApproval(Builder $query): Builder
    {
        return $query->where('approval_status', self::STATUS_PENDING);
    }

    /**
     * Get Available permissions for model
     * 
     * @return array
     */
    public static function getPermissions(): array
    {
        return defaultModelPermissions();
    }
}
