<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralRequest extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPORVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';

    
    /**
     * Get list of statuses
     *
     * @param  bool $asKeys
     * @return array
     */
    public static function getStatuses(bool $asKeys = false): array
    {
        $statuses = [
            self::STATUS_PENDING => ucwords(self::STATUS_PENDING),
            self::STATUS_APPORVED => ucwords(self::STATUS_APPORVED),
            self::STATUS_DISAPPROVED => ucwords(self::STATUS_DISAPPROVED),
        ];

        return $asKeys ? $statuses : array_keys($statuses);
    }
}
