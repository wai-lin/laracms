<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Get a temporary URL for an S3 file with caching
     *
     * @param  string  $filePath  The path to the file in S3
     * @param  int  $expireMinutes  How long the URL should be valid (default: 60)
     * @return string The temporary URL
     */
    public static function temporaryUrl(string $filePath, int $expireMinutes = 60): string
    {
        if (empty($filePath)) {
            return '';
        }

        $cacheKey = 'temp_url:'.md5($filePath);

        // Cache the URL for expireMinutes - 5 to ensure refresh before actual expiry
        return Cache::remember($cacheKey, now()->addMinutes($expireMinutes - 5), function () use ($filePath, $expireMinutes) {
            return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes($expireMinutes));
        });
    }

    /**
     * Get a public URL for an S3 file
     *
     * @param  string  $filePath  The path to the file in S3
     * @return string The public URL
     */
    public static function publicUrl(string $filePath): string
    {
        if (empty($filePath)) {
            return '';
        }

        return Storage::disk('s3')->url($filePath);
    }

    /**
     * Clear the temporary URL cache for a file
     *
     * @param  string  $filePath  The path to the file in S3
     */
    public static function clearTemporaryUrlCache(string $filePath): void
    {
        $cacheKey = 'temp_url:'.md5($filePath);
        Cache::forget($cacheKey);
    }
}
