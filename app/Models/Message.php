<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    const CHANNEL_EMAIL = 'email';

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'designation_id',
        'channel',
        'subject',
        'body',
        'is_draft',
    ];

    /**
     * Designation that the messaage is to be sent to.
     *
     * @return BelongsTo
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Recipients of the message.
     * 
     * @return BelongsToMany
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_user', 'message_id', 'user_id');
    }

    /**
     * Get list of channels
     *
     * @param boolean $asKeys
     * @return array
     */
    public static function getChannels($asKeys = false): array
    {
        $channels = [
            self::CHANNEL_EMAIL => ucfirst(self::CHANNEL_EMAIL),
        ];

        return $asKeys ? $channels : array_keys($channels);
    }

    /**
     * Get default validation rules.
     *
     * @return array
     */
    public static function defaultValidationRules(): array
    {
        $rules = [
            'designation_id' => 'required|exists:designations,id',
            'channel' => 'sometimes|nullable|in:'.implode(',', self::getChannels()),
            'subject' => 'sometimes|nullable|string|max:255',
            'body' => 'required|string|max:65000',
            'is_draft' => 'sometimes|nullable|boolean',
        ];

        return $rules;
    }

    /**
     * Get body without tags
     *
     * @return string
     */
    public function getSafeContentAttribute(): string
    {
        return strip_tags($this->body);
    }
}
