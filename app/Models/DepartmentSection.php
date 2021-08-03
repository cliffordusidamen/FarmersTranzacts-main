<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class DepartmentSection extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'department_id',
        'name',
        'description',
    ];

    /**
     * Relationship: Belongs to department
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
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
        ];

        $rules = [
            'name' => $nameRules,
            'description' => 'sometimes|nullable|string|min:10|max:' . INPUT_TEXTAREA_MAXLENGTH,
        ];

        return $rules;
    }
}
