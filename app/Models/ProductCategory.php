<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Mutator: create unique slug, based on title
     * 
     * @param  string  $value
     */
    public function setTitleAttribute($value)
    {
        if (
            empty($this->id)
            && !empty(trim($value))
            && empty(trim($this->attributes['slug']))
        ) {
            $this->attributes['slug'] = Str::slug($value);
        }

        $this->attributes['name'] = $value;
    }
}
