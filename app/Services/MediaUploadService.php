<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaUploadService
{
    private const DEFAULT_EXTENSIONS = ['png', 'jpg', 'jpeg', 'webp'];

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

    private function store(UploadedFile $file, string $folder, array $extensions): ?array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $extensions, true)) {
            return null;
        }

        $datePath = date('Y/m/d');
        $folderPath = trim($folder, '/');
        $relativeDirectory = trim(($folderPath ? $folderPath . '/' : '') . $datePath, '/');
        $targetDirectory = public_path('uploads/' . $relativeDirectory);

        File::ensureDirectoryExists($targetDirectory, 0755, true);

        $filename = date('Y-m-d__') . Str::random(32) . '.' . $ext;
        $file->move($targetDirectory, $filename);

        return [
            'name' => $filename,
            'path' => $targetDirectory . DIRECTORY_SEPARATOR,
            'path_img' => 'uploads/' . $relativeDirectory . '/' . $filename,
        ];
    }
}
