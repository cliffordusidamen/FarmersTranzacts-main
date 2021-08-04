<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    use HasFactory;

    const ADMIN_ID = 1;
    const SHOPPER_ID = 2;
    const VENDOR_ID = 3;

    const ADMIN_NAME = 'admin';
    const SHOPPER_NAME = 'shopper';
    const VENDOR_NAME = 'vendor';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    /**
     * Relationship: Has many users
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'designation_id');
    }

    /**
     * Get list of roles
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getList(bool $asKeys = false): array
    {
        $types = [
            self::ADMIN_ID => ucwords(self::ADMIN_NAME),
            self::SHOPPER_ID => ucwords(self::SHOPPER_NAME),
            self::VENDOR_ID => ucwords(self::VENDOR_NAME),
        ];

        return $asKeys ? $types : array_keys($types);
    }

    /**
     * Get permissions for the currently authenticated users
     * 
     * @return array
     */
    public static function getUserPermissions(): array
    {
        $permissions = [
            'canManageProductCategories' =>  userIsSuperAdmin() || userIsAdminDesignation(),
        ];

        return $permissions;
    }
}
