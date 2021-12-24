<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agent extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationship: belongs to user
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationships: belongs to spending volume
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function spendingVolume(): BelongsTo
    {
        return $this->belongsTo(AgentSpendingVolume::class, 'spending_volume_id');
    }

    /**
     * Relatioship: belongs to address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * Relatioship: belongs to many product categories
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class);
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
