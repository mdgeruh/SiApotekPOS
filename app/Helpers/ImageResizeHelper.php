<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageResizeHelper
{
    /**
     * Resize logo image and delete old one
     */
    public static function resizeLogo(UploadedFile $file, ?string $oldLogoPath = null): ?string
    {
        try {
            // Delete old logo if exists
            if ($oldLogoPath) {
                self::deleteImage($oldLogoPath);
            }

            // Generate unique filename
            $filename = 'logo_' . now()->format('Y-m-d_H-i-s') . '_' . Str::random(8) . '.png';
            $path = 'logos/' . $filename;

            // Resize and save image
            $resizedImage = self::resizeImage($file, 200, 200);
            if ($resizedImage) {
                Storage::disk('public')->put($path, $resizedImage);
                return $path;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error resizing logo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Resize favicon image and delete old one
     */
    public static function resizeFavicon(UploadedFile $file, ?string $oldFaviconPath = null): ?string
    {
        try {
            // Delete old favicon if exists
            if ($oldFaviconPath) {
                self::deleteImage($oldFaviconPath);
            }

            // Generate unique filename
            $filename = 'favicon_' . now()->format('Y-m-d_H-i-s') . '_' . Str::random(8) . '.png';
            $path = 'favicons/' . $filename;

            // Resize and save image
            $resizedImage = self::resizeImage($file, 64, 64);
            if ($resizedImage) {
                Storage::disk('public')->put($path, $resizedImage);
                return $path;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error resizing favicon: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Resize profile photo and delete old one
     */
    public static function resizeProfilePhoto(UploadedFile $file, ?string $oldPhotoPath = null, $userId = null): ?string
    {
        try {
            // Delete old profile photo if exists
            if ($oldPhotoPath) {
                self::deleteImage($oldPhotoPath);
            }

            // Generate unique filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $userId = $userId ?? (auth()->id() ?? 'unknown');
            $filename = "profile_{$userId}_{$timestamp}_" . Str::random(8) . '.png';
            $path = 'profile-photos/' . $filename;

            // Resize and save image
            $resizedImage = self::resizeImage($file, 300, 300);
            if ($resizedImage) {
                Storage::disk('public')->put($path, $resizedImage);
                return $path;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error resizing profile photo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete image from storage
     */
    public static function deleteImage(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        try {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($imagePath)) {
                return Storage::disk('public')->delete($imagePath);
            }
            return true; // File doesn't exist, consider it "deleted"
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get thumbnail URL with cache busting
     */
    public static function getThumbnailUrl(?string $imagePath, int $width = 40, int $height = 40): string
    {
        if (!$imagePath) {
            return asset('images/default-logo.svg');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($imagePath)) {
            return asset('images/default-logo.svg');
        }

        // Add timestamp for cache busting
        $timestamp = Storage::disk('public')->lastModified($imagePath);
        $url = asset('storage/' . $imagePath) . '?v=' . $timestamp;

        return $url;
    }

    /**
     * Resize image using GD library
     */
    private static function resizeImage(UploadedFile $file, int $targetWidth, int $targetHeight): ?string
    {
        try {
            // Get image info
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                return null;
            }

            $originalWidth = $imageInfo[0];
            $originalHeight = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // Create image resource
            $sourceImage = self::createImageResource($file->getPathname(), $mimeType);
            if (!$sourceImage) {
                return null;
            }

            // Calculate new dimensions maintaining aspect ratio
            $ratio = min($targetWidth / $originalWidth, $targetHeight / $originalHeight);
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);

            // Create target canvas with white background
            $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
            $white = imagecolorallocate($targetImage, 255, 255, 255);
            imagefill($targetImage, 0, 0, $white);

            // Calculate centering position
            $x = ($targetWidth - $newWidth) / 2;
            $y = ($targetHeight - $newHeight) / 2;

            // Resize and copy image
            imagecopyresampled(
                $targetImage, $sourceImage,
                $x, $y, 0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );

            // Output to string
            ob_start();
            imagepng($targetImage, null, 9);
            $imageData = ob_get_contents();
            ob_end_clean();

            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($targetImage);

            return $imageData;
        } catch (\Exception $e) {
            Log::error('Error in resizeImage: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create image resource from file
     */
    private static function createImageResource(string $filePath, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($filePath);
            case 'image/png':
                return imagecreatefrompng($filePath);
            case 'image/gif':
                return imagecreatefromgif($filePath);
            case 'image/webp':
                return imagecreatefromwebp($filePath);
            default:
                return null;
        }
    }

    /**
     * Clean up old images from storage (utility method)
     */
    public static function cleanupOldImages(string $directory, int $daysOld = 30): int
    {
        try {
            $disk = Storage::disk('public');
            $files = $disk->files($directory);
            $deletedCount = 0;
            $cutoffTime = now()->subDays($daysOld)->timestamp;

            foreach ($files as $file) {
                $lastModified = $disk->lastModified($file);
                if ($lastModified < $cutoffTime) {
                    if ($disk->delete($file)) {
                        $deletedCount++;
                    }
                }
            }

            return $deletedCount;
        } catch (\Exception $e) {
            Log::error('Error cleaning up old images: ' . $e->getMessage());
            return 0;
        }
    }
}
