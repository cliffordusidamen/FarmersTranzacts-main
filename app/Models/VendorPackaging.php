<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class VendorPackaging extends Model
{
    use HasFactory;

    protected $table = 'vendor_packagings';

    /**
     * mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: Has many products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'packaging_id');
    }

    /**
     * Validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        $uniqueRule = Rule::unique('vendor_packagings', 'name');

        if (!is_null($id)) {
            $uniqueRule->ignore((int) $id);
        }

        return [
            'name' => ['required', 'string', 'min:3', 'max:100', $uniqueRule],
            'description' => 'max:500',
        ];
    }
}
