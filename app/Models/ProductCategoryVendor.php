<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategoryVendor extends Pivot
{
    /**
     * Has Auto incrementing field
     * 
     * @var bool
     */
    public $incrementing = true;
}
