<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserVendor extends Pivot
{
    /**
     * Has Auto incrementing field
     * 
     * @var bool
     */
    public $incrementing = true;
}
