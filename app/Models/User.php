<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\StaffRegistrationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
        'is_farmer',
        'date_of_birth',
        'require_password_change',
        'referred_by',
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
     * Relationship: interested product categories
     * 
     * @return BelongsToMany
     */
    public function interestedProductCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_category_user',
            'user_id',
            'product_category_id'
        );
    }

    /**
     * Relationship: has one agent
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function agent(): HasOne
    {
        return $this->hasOne(Agent::class);
    }

    /**
     * Relationship: Has many orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Belong to referrer
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Has many referral request
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referralRequests(): HasMany
    {
        return $this->hasMany(ReferralRequest::class, 'user_id');
    }

    /**
     * Has many referral request
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Has one farm
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function farm(): HasOne
    {
        return $this->hasOne(Farm::class);
    }


    /**
     * Messages sent to this user
     *
     * @return BelongsToMany
     */
    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class);
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
            Designation::AGENT_NAME    => Designation::AGENT_ID,
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

    /**
     * Default validation rules
     * 
     * @param int|null $id
     * @return array
     */
    public static function defaultValidationRules(?int $id = null): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'phone' => ['nullable', 'min:7', 'max:17', Rule::unique('users', 'phone')->ignore($id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];

        return $rules;
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $urlPath = "/reset-password/{$token}?email={$this->email}";
        $url = url($urlPath);

        $isShopper = $this->designation_id == Designation::SHOPPER_ID;
        $isAgent = $this->designation_id == Designation::AGENT_ID;

        if (!$isShopper && !$isAgent) {
            $url = $this->designation_id == Designation::SELLER_ID
                ? config('app.seller_app_url')
                : config('app.admin_app_url');

            $url .= $urlPath;
        }

        $this->notify(new ResetPasswordNotification($url));
    }
}
