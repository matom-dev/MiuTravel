<?php

namespace Tests\Feature;

use App\Services\MediaUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MediaUploadServiceTest extends TestCase
{
    public function test_it_uploads_one_image_from_the_current_request(): void
    {
        $file = UploadedFile::fake()->image('cover.jpg', 320, 240);
        request()->files->set('images', $file);

        $result = app(MediaUploadService::class)->uploadOne('images', 'tests');

        $this->assertSame(1, $result['code']);
        $this->assertStringEndsWith('.jpg', $result['name']);
        $this->assertFileExists(public_path($result['path_img']));

        File::delete(public_path($result['path_img']));
    }

    public function test_it_returns_code_zero_when_no_file_is_present(): void
    {
        $result = app(MediaUploadService::class)->uploadOne('missing');

        $this->assertSame(['code' => 0], $result);
    }
}
