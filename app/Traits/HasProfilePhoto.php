<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasProfilePhoto
{
    /**
     * Generate unique filename for profile photo
     */
    protected function generateProfilePhotoName($file, $userId = null): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $userId = $userId ?? (auth()->id() ?? 'unknown');
        $extension = $file->getClientOriginalExtension();
        $randomString = Str::random(8);

        return "profile_{$userId}_{$timestamp}_{$randomString}.{$extension}";
    }

    /**
     * Upload profile photo and return the path
     */
    protected function uploadProfilePhoto(UploadedFile $file, $userId = null): string
    {
        $filename = $this->generateProfilePhotoName($file, $userId);
        return $file->storeAs('profile-photos', $filename, 'public');
    }

    /**
     * Delete profile photo if exists
     */
    protected function deleteProfilePhoto(?string $photoPath): bool
    {
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            return Storage::disk('public')->delete($photoPath);
        }
        return false;
    }

    /**
     * Update profile photo with cleanup of old photo
     */
    protected function updateProfilePhoto(UploadedFile $file, $userId = null, ?string $oldPhotoPath = null): string
    {
        // Delete old photo if exists
        if ($oldPhotoPath) {
            $this->deleteProfilePhoto($oldPhotoPath);
        }

        // Upload new photo
        return $this->uploadProfilePhoto($file, $userId);
    }
}
