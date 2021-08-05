<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    /**
     * Validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        $uniqueRule = 'unique:roles,name' . (!is_null($id) ? ",{$id}" : '');

        return [
            'name' => ['required', 'min:3', 'max:255', $uniqueRule, ],
            'description' => ['nullable', 'min:15', 'max:255', ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Guard scope
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSanctumGuard(Builder $query): Builder
    {
        return $query->where('guard_name', 'sanctum');
    }
}
