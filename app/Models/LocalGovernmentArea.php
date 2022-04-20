<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalGovernmentArea extends Model
{
    use HasFactory;

    public static function listGroupedByStateId()
    {
        $allStates = State::with(['localGovtAreas'])->get();
        $stateLgas = [];

        foreach ($allStates as $state) {
            if (!isset($stateLgas[$state->id])) {
                $stateLgas[$state->id] = [];
            }
            $stateLgas[$state->id] = $state->localGovtAreas()->pluck('local_govt_name', 'id');
        }

        return $stateLgas;
    }
}
