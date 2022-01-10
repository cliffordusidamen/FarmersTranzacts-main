<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    use HasFactory;

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationship: belongs to help page
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function helpPage(): BelongsTo
    {
        return $this->belongsTo(HelpPage::class);
    }

    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function defaultValidationRules(): array
    {
        $rules = [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'help_page_id' => 'sometimes|nullable|exists:help_pages,id',
        ];

        return $rules;
    }
}
