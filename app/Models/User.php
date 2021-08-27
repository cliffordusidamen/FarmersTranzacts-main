<?php

namespace App\Models;

use App\Notifications\StaffRegistrationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    const GENDER_FEMALE = 'female';
    const GENDER_MALE = 'male';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'designation_id',
        'is_active',
        'department_id',
        'department_section_id',
        'is_admin',
        'require_password_change',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Relationship: The vendors that belong to the user.
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class);
    }

    /**
     * Relationship: The vendors that belong to the user.
     */
    public function unapprovedVendors(): BelongsToMany
    {
        return $this->vendors()->where('approval_status', Vendor::STATUS_PENDING);
    }

    /**
     * Relationship: The designation of the user.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    /**
     * Relationship: Belongs to department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Relationship: Belongs to department_section
     */
    public function departmentSection(): BelongsTo
    {
        return $this->belongsTo(DepartmentSection::class, 'department_section_id');
    }

    /**
     * Relationship: Has one address
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'user_id');
    }

    /**
     * Setter for "password" field
     * 
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get full name
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->first_name . ' ' . $this->last_name));
    }

    /**
     * Check if user is designated as staff
     * 
     * @return string
     */
    public function getIsStaffAttribute()
    {
        Log::info($this->toArray());
        return $this->designation_id == Designation::STAFF_ID;
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $gender = $this->gender ?? 'male';
        return "/img/user_{$gender}_default.jpg";
    }

    /**
     * Get vendor information of this user
     * 
     * @return \App\Models\Vendor|null
     */
    public function getVendorDataAttribute()
    {
        $vendorData = $this->vendors()
            ->with(['productCategories', 'address'])
            ->first();

        return $vendorData;
    }

    /**
     * filter by designation
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $designationName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDesignated(Builder $query, $designationName): Builder
    {
        $designations = [
            Designation::STAFF_NAME     => Designation::STAFF_ID,
            Designation::SHOPPER_NAME   => Designation::SHOPPER_ID,
            Designation::SELLER_NAME    => Designation::SELLER_ID,
        ];

        $designationName = strtolower($designationName);
        $designationId = $designations[$designationName] ?? 0;
        return $query->where('designation_id', $designationId);
    }

    /**
     * filter by name, email and phone
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $searchText
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterNameAndContact(Builder $query, $searchText): Builder
    {
        $searchText = trim($searchText);

        if (!isset($searchText[2])) {
            return $query;
        }

        return $query->where(function(Builder $q) use ($searchText) {
            $q->where(DB::raw('CONCAT(last_name, " ", first_name)'), 'LIKE', "%{$searchText}%")
                ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "%{$searchText}%")
                ->orWhere('email', 'LIKE', "%{$searchText}%")
                ->orWhere('phone', 'LIKE', "%{$searchText}%");
        });
    }

    /**
     * Send email for registration notification with auto-generated password
     * 
     * @return void
     */
    public function sendStaffWelcomeNotification($password)
    {
        $this->notify(new StaffRegistrationNotification($password));
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
