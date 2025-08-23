<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasAudit
{
    /**
     * Boot the trait
     */
    protected static function bootHasAudit()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

    /**
     * Get the user who created the record
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the record
     */
    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    /**
     * Check if record was created by specific user
     */
    public function isCreatedBy($userId): bool
    {
        return $this->created_by == $userId;
    }

    /**
     * Check if record was updated by specific user
     */
    public function isUpdatedBy($userId): bool
    {
        return $this->updated_by == $userId;
    }

    /**
     * Check if record was deleted by specific user
     */
    public function isDeletedBy($userId): bool
    {
        return $this->deleted_by == $userId;
    }
}
