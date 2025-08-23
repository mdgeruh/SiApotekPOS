<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role_id',
        'profile_photo_path',
        'birth_date',
        'gender',
        'address',
        'status',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'settings' => 'array',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function hasRole($role)
    {
        return $this->role->name === $role;
    }

    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Check if user has profile photo path
        if ($this->profile_photo_path) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->profile_photo_path)) {
                return asset('storage/' . $this->profile_photo_path);
            }
        }

        // Return default avatar if no profile photo or file doesn't exist
        return asset('images/default-avatar.svg');
    }

    /**
     * Get user settings with defaults
     */
    public function getSettingsAttribute($value)
    {
        $defaults = [
            'notification_email' => false,
            'notification_sms' => false,
            'language' => 'id',
            'timezone' => 'Asia/Jakarta',
            'theme' => 'light',
            'sidebar_collapsed' => 'auto',
            'compact_mode' => false,
        ];

        if ($value) {
            $settings = is_array($value) ? $value : json_decode($value, true);
            return array_merge($defaults, $settings ?: []);
        }

        return $defaults;
    }

    /**
     * Update user's profile photo
     */
    public function updateProfilePhoto($photoPath)
    {
        // Delete old profile photo if exists
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            Storage::disk('public')->delete($this->profile_photo_path);
        }

        // Update profile photo path
        $this->update(['profile_photo_path' => $photoPath]);

        return $this;
    }

    /**
     * Delete user's profile photo
     */
    public function deleteProfilePhoto()
    {
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            Storage::disk('public')->delete($this->profile_photo_path);
        }

        $this->update(['profile_photo_path' => null]);

        return $this;
    }
}
