<?php

namespace App\Support;

class AssetVersion
{
    /**
     * Build a public asset URL with a cache-busting query string based on
     * the file's last modification time. Falls back to a static version
     * stamp when the file is not present yet (e.g. before deploy).
     */
    public static function url(string $path): string
    {
        $publicPath = public_path($path);
        $version = file_exists($publicPath) ? (string) filemtime($publicPath) : '1';

        return asset($path).'?v='.$version;
    }
}
