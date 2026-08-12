<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadService
{
    private const DEFAULT_EXTENSIONS = ['png', 'jpg', 'jpeg', 'webp'];
    private const MAX_BYTES = 5 * 1024 * 1024;
    private const MAX_DIMENSION = 8000;
    private const MIME_BY_EXTENSION = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
    ];

    public function uploadOne(string $field, string $folder = '', array $extensions = []): array
    {
        $file = request()->file($field);

        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return ['code' => 0];
        }

        $stored = $this->store($file, $folder, $extensions ?: self::DEFAULT_EXTENSIONS);

        return $stored ? array_merge(['code' => 1], $stored) : ['code' => 0];
    }

    public function uploadMany(string $field, string $folder = '', array $extensions = []): array
    {
        $files = request()->file($field, []);

        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        if (!is_array($files)) {
            return [];
        }

        $uploaded = [];
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile || !$file->isValid()) {
                continue;
            }

            $stored = $this->store($file, $folder, $extensions ?: self::DEFAULT_EXTENSIONS);
            if ($stored) {
                $uploaded[] = $stored['name'];
            }
        }

        return $uploaded;
    }

    public function deleteImage(?string $image, string $folder = ''): void
    {
        if (!$image) {
            return;
        }

        foreach ($this->candidateRelativePaths($image, $folder) as $relativePath) {
            if (Storage::disk('uploads')->exists($relativePath)) {
                Storage::disk('uploads')->delete($relativePath);
            }

            $thumbnail = $this->thumbnailPath($relativePath);
            if (Storage::disk('uploads')->exists($thumbnail)) {
                Storage::disk('uploads')->delete($thumbnail);
            }
        }
    }

    public function thumbnailName(string $image): string
    {
        return 'thumbnails/' . ltrim($image, '/');
    }

    private function store(UploadedFile $file, string $folder, array $extensions): ?array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (!$this->isAllowedImage($file, $ext, $extensions)) {
            return null;
        }

        $datePath = date('Y/m/d');
        $folderPath = trim($folder, '/');
        $relativeDirectory = trim(($folderPath ? $folderPath . '/' : '') . $datePath, '/');
        $filename = date('Y-m-d__') . Str::random(32) . '.' . $ext;
        $relativePath = $relativeDirectory . '/' . $filename;

        Storage::disk('uploads')->putFileAs($relativeDirectory, $file, $filename);
        $this->createThumbnail($relativePath, $file->getMimeType());

        return [
            'name' => $filename,
            'path' => public_path('uploads/' . $relativeDirectory) . DIRECTORY_SEPARATOR,
            'path_img' => 'uploads/' . $relativeDirectory . '/' . $filename,
            'thumbnail' => $this->thumbnailPath($relativePath),
            'thumbnail_path_img' => 'uploads/' . $this->thumbnailPath($relativePath),
        ];
    }

    private function isAllowedImage(UploadedFile $file, string $ext, array $extensions): bool
    {
        if (!in_array($ext, $extensions, true) || !isset(self::MIME_BY_EXTENSION[$ext])) {
            return false;
        }

        if ($file->getSize() === false || $file->getSize() > self::MAX_BYTES) {
            return false;
        }

        $realPath = $file->getRealPath();
        if (!$realPath || $this->looksLikeTextPayload($realPath)) {
            return false;
        }

        $mime = $file->getMimeType();
        if ($mime !== self::MIME_BY_EXTENSION[$ext]) {
            return false;
        }

        $size = @getimagesize($realPath);
        if (!$size || empty($size[0]) || empty($size[1])) {
            return false;
        }

        return $size[0] <= self::MAX_DIMENSION && $size[1] <= self::MAX_DIMENSION;
    }

    private function looksLikeTextPayload(string $path): bool
    {
        $chunk = strtolower((string) file_get_contents($path, false, null, 0, 2048));

        return str_contains($chunk, '<svg')
            || str_contains($chunk, '<html')
            || str_contains($chunk, '<script')
            || str_contains($chunk, '<?php');
    }

    private function createThumbnail(string $relativePath, ?string $mime): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            return;
        }

        $sourcePath = Storage::disk('uploads')->path($relativePath);
        [$width, $height] = @getimagesize($sourcePath) ?: [0, 0];
        if ($width <= 0 || $height <= 0) {
            return;
        }

        $max = 360;
        $ratio = min($max / $width, $max / $height, 1);
        $thumbWidth = max(1, (int) round($width * $ratio));
        $thumbHeight = max(1, (int) round($height * $ratio));

        $source = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (!$source) {
            return;
        }

        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

        $thumbnailPath = $this->thumbnailPath($relativePath);
        Storage::disk('uploads')->makeDirectory(dirname($thumbnailPath));
        $targetPath = Storage::disk('uploads')->path($thumbnailPath);

        match ($mime) {
            'image/jpeg' => imagejpeg($thumb, $targetPath, 82),
            'image/png' => imagepng($thumb, $targetPath, 6),
            'image/webp' => function_exists('imagewebp') ? imagewebp($thumb, $targetPath, 82) : null,
            default => null,
        };

        imagedestroy($source);
        imagedestroy($thumb);
    }

    private function thumbnailPath(string $relativePath): string
    {
        return trim(dirname($relativePath), '.') . '/thumbnails/' . basename($relativePath);
    }

    private function candidateRelativePaths(string $image, string $folder = ''): array
    {
        $image = ltrim(str_replace('\\', '/', $image), '/');
        $image = preg_replace('#^uploads/#', '', $image);

        if (str_contains($image, '/')) {
            return [$image];
        }

        $datePart = str_replace('_', '/', explode('__', $image)[0] ?? '');
        $timestamp = strtotime($datePart);
        if (!$timestamp || $timestamp <= mktime(0, 0, 0, 1, 1, 2000)) {
            return [trim($folder, '/') . '/' . $image, $image];
        }

        $folderPath = trim($folder, '/');
        $relativeDirectory = trim(($folderPath ? $folderPath . '/' : '') . date('Y/m/d', $timestamp), '/');

        return [$relativeDirectory . '/' . $image];
    }
}
