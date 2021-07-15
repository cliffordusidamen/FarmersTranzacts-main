<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'user_id',
        'state_id',
        'line_1',
        'line_2',
        'city',
    ];

    /**
     * Relationship: Belongs to user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Belongs to vendor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relationship: Belongs to user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
