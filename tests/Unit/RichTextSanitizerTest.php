<?php

namespace Tests\Unit;

use App\Services\RichTextSanitizer;
use PHPUnit\Framework\TestCase;

class RichTextSanitizerTest extends TestCase
{
    public function test_it_keeps_common_editor_markup_and_removes_script_vectors(): void
    {
        $html = '<h2 onclick="bad()">Xin chào</h2><p>Đẹp <strong>lắm</strong></p><script>alert(1)</script><a href="javascript:alert(1)">xem</a>';

        $clean = (new RichTextSanitizer())->clean($html);

        $this->assertStringContainsString('<h2>Xin chào</h2>', $clean);
        $this->assertStringContainsString('<strong>lắm</strong>', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
    }
}
