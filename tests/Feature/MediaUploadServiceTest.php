<?php

namespace Tests\Feature;

use App\Services\MediaUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadServiceTest extends TestCase
{
    public function test_it_uploads_one_image_from_the_current_request(): void
    {
        Storage::fake('uploads');

        $file = UploadedFile::fake()->image('cover.jpg', 320, 240);
        request()->files->set('images', $file);

        $result = app(MediaUploadService::class)->uploadOne('images', 'tests');
        $relativePath = preg_replace('#^uploads/#', '', $result['path_img']);

        $this->assertSame(1, $result['code']);
        $this->assertStringEndsWith('.jpg', $result['name']);
        Storage::disk('uploads')->assertExists($relativePath);
        Storage::disk('uploads')->assertExists($result['thumbnail']);
    }

    public function test_it_returns_code_zero_when_no_file_is_present(): void
    {
        $result = app(MediaUploadService::class)->uploadOne('missing');

        $this->assertSame(['code' => 0], $result);
    }

    public function test_it_rejects_svg_html_or_fake_image_payloads(): void
    {
        Storage::fake('uploads');

        $fakeJpg = UploadedFile::fake()->createWithContent('payload.jpg', '<svg onload="alert(1)"></svg>');
        request()->files->set('images', $fakeJpg);

        $this->assertSame(['code' => 0], app(MediaUploadService::class)->uploadOne('images'));
        $this->assertCount(0, Storage::disk('uploads')->allFiles());
    }

    public function test_it_deletes_original_and_thumbnail_files(): void
    {
        Storage::fake('uploads');

        $file = UploadedFile::fake()->image('cover.webp', 320, 240);
        request()->files->set('images', $file);

        $result = app(MediaUploadService::class)->uploadOne('images');
        $relativePath = preg_replace('#^uploads/#', '', $result['path_img']);

        app(MediaUploadService::class)->deleteImage($result['name']);

        Storage::disk('uploads')->assertMissing($relativePath);
        Storage::disk('uploads')->assertMissing($result['thumbnail']);
    }
}
