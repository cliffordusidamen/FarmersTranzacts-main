<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorBankAccount extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     * 
     * @var array
     */
    protected $fillable = [
        'bank_id',
        'account_name',
        'account_number',
        'is_default',
    ];

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
     * Relationship: Belongs to bank
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function defaultValidationRules(): array
    {
        $rules = [
            'bank_id' => 'required|integer|exists:banks,id',
            'account_name' => 'required|string|min:5|max:100',
            'account_number' => 'required|string|min:5|max:25',
            'is_default' => 'sometimes|boolean',
        ];

        return $rules;
    }
}
