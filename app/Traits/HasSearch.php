<?php

namespace App\Traits;

trait HasSearch
{
    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        $searchableFields = $this->searchableFields ?? ['name'];

        $query->where(function ($q) use ($search, $searchableFields) {
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'like', '%' . $search . '%');
            }
        });

        return $query;
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Scope for ordering
     */
    public function scopeOrderByField($query, $field = 'created_at', $direction = 'desc')
    {
        $allowedFields = $this->orderableFields ?? ['created_at', 'name', 'id'];
        $allowedDirections = ['asc', 'desc'];

        if (in_array($field, $allowedFields) && in_array($direction, $allowedDirections)) {
            return $query->orderBy($field, $direction);
        }

        return $query->orderBy('created_at', 'desc');
    }
}
