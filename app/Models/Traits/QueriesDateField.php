<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueriesDateField
{

    /**
     * Find by date field within a particular date range
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $dateField
     * @param  string|null $dateFrom
     * @param  string|null $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function _dated(
        Builder $query,
        string $dateField = 'created_at',
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): Builder {
        if ($dateFrom) {
            $query->whereDate($dateField, '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate($dateField, '<=', $dateTo);
        }
        return $query;
    }

    /**
     * Find by date field, within the current month
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $dateField
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function _thisMonth(
        Builder $query,
        string $dateField = 'created_at'
    ): Builder {
        return $this->_dated(
            $query,
            $dateField,
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        );
    }
}