<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        'is_active',
        'display_picture_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Has many sub categories
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(ProductSubcategory::class, 'category_id');
    }

    /**
     * Relationship: Has many products
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Mutator: create unique slug, based on name
     * 
     * @param  string  $value
     */
    public function setNameAttribute($value)
    {
        if (
            empty($this->id)
            && !empty(trim($value))
            && empty($this->attributes['slug'])
        ) {
            $this->attributes['slug'] = Str::slug($value);
        }

        $this->attributes['name'] = $value;
    }

    /**
     * Validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        $uniqueRule = 'unique:product_categories,name' . (!is_null($id) ? ",{$id}" : '');

        return [
            'name' => ['required', 'min:3', 'max:255', $uniqueRule, ],
            'description' => ['nullable', 'min:15', 'max:255', ],
            'is_active' => ['sometimes', 'boolean'],
            'display_picture_path' => ['sometimes', 'nullable', 'mimes:png,jpg', 'max:1024'],
        ];
    }

    /**
     * Get Available permissions for model
     * 
     * @return array
     */
    public static function getPermissions(): array
    {
        return defaultModelPermissions();
    }

    /**
     * Get the URL to the display picture.
     *
     * @return string
     */
    public function getDisplayPictureAttribute()
    {
        return $this->display_picture_path
            ? Storage::disk()->url($this->display_picture_path)
            : '';
    }

    /**
     * Add category picture.
     *
     * @param  \Illuminate\Http\UploadedFile  $picture
     * @return bool|string
     */
    public function addDisplayPicture(UploadedFile $picture)
    {
        $path = false;
        tap($this->display_picture_path, function ($previous) use ($picture, &$path) {
            $path =$picture->storePublicly('public/product-category-pictures/');
            $this->forceFill([
                'display_picture_path' => $path,
            ])->save();

            if ($previous) {
                Storage::disk()->delete($previous);
            }
        });

        return $path;
    }
}
