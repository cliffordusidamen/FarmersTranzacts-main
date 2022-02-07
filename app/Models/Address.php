<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\Rule;

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
        'line_1',
        'line_2',
        'city',
        'postal_code',
        'state_id',
        'country_id',
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
     * Relationship: Belongs to state
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Relationship: Belongs to country
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relationship: Has one order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'address_id');
    }

    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function validationRules(): array
    {
        $rules = [
            'line_1' => ['required', 'string', 'min:3', 'max:200'],
            'line_2' => ['sometimes', 'string', 'nullable', 'min:3', 'max:200'],
            'city' => ['required', 'string', 'nullable', 'min:3', 'max:25'],
            'postal_code' => ['sometimes', 'string', 'nullable', 'min:3', 'max:10'],
            'state_id' => ['required', 'exists:states,id'],
            'country_id' => ['sometimes', 'nullable', 'exists:countries,id'],
            'order_for_self' => 'sometimes|nullable|boolean',
            'referral' => [
                'sometimes',
                'nullable',
                'exists:users,id',
                Rule::requiredIf(function () {
                    return 
                        authUserIsAgent()
                        && empty(request('referral'))
                        && empty(request('order_for_self'));
                }),
            ],
        ];

        return $rules;
    }
}
