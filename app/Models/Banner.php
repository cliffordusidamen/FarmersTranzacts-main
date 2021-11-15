<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    const STORAGE_PATH = 'public/advert-banners';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
    /**
     * Save the image to the storage
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return bool
     */
    public function saveBannerImage(?UploadedFile $photo = null): bool
    {
        if (is_null($photo)) {
            return false;
        }

        $saved = false;
        tap($this->image_path, function ($previous) use ($photo, &$saved) {
            $this->forceFill([
                'image_path' => $photo->storePublicly(
                    self::STORAGE_PATH
                ),
            ])->save();

            if ($previous) {
                Storage::disk()->delete($previous);
            }
            $saved = true;
        });

        return $saved;
    }

    /**
     * Delete the attached image
     *
     * @return bool
     */
    public function deleteBannerImage(): bool
    {
        $deleted = false;
        if ($this->image_path) {
            $deleted = Storage::disk()->delete($this->image_path);
            $this->image_path = $deleted ? null : $this->image_path;
        }

        return $deleted;
    }

    /**
     * Delete the banner and attached image
     *
     * @return bool
     */
    public function deleteWithImage(): bool
    {
        $deleted = false;
        if ($this->image_path) {
            $deleted = Storage::disk()->delete($this->image_path);
        }

        if ($deleted) {
            return $this->delete() ? true : false;
        }

        return false;
    }

    /**
     * Get the URL to banner image
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path
                ? Storage::disk()->url($this->image_path)
                : null;
    }

    /**
     * Default validation rules
     * 
     * @param  int|null  $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        $imageRules = is_null($id)
            ? 'required|image|mimes:jpeg,png,jpg|max:1024'
            : 'image|mimes:jpeg,png,jpg|max:1024';
        $rules = [
            'name' => 'required|min:3|max:191',
            'image' => $imageRules,
        ];

        return $rules;
    }
}
