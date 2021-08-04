<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class Department extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: Has many sections
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections(): HasMany
    {
        return $this->hasMany(DepartmentSection::class);
    }

    /**
     * Relationship: Has many users
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }

    /**
     * Default validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules($id = null): array
    {
        $nameRules = [
            'required',
            'string',
            'min:2',
            'max:' . DATABASE_STRING_MAXLENGTH,
            Rule::unique('departments', 'name'),
        ];

        if (!is_null($id)) {
            $nameRules[4]->ignore((int) $id);
        }

        $rules = [
            'name' => $nameRules,
            'description' => 'sometimes|nullable|string|min:15|max:' . INPUT_TEXTAREA_MAXLENGTH,
        ];

        return $rules;
    }
}
