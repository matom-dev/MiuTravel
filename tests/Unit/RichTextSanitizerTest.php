<?php

namespace Tests\Unit;

use App\Services\RichTextSanitizer;
use Tests\TestCase;

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

    public function test_it_removes_img_href_style_and_data_uri_xss_payloads(): void
    {
        $html = '<p style="background:url(javascript:alert(1))">Nội dung</p>'
            . '<img src="data:text/html;base64,PHNjcmlwdD4xPC9zY3JpcHQ+" onerror="alert(1)" style="width:100px">'
            . '<img src="https://example.com/photo.jpg" onload="alert(1)">'
            . '<a href="data:text/html,<script>alert(1)</script>" style="color:red">bad</a>'
            . '<a href="https://example.com" target="_blank" onclick="alert(1)">good</a>';

        $clean = (new RichTextSanitizer())->clean($html);

        $this->assertStringContainsString('Nội dung', $clean);
        $this->assertStringContainsString('https://example.com/photo.jpg', $clean);
        $this->assertStringContainsString('href="https://example.com"', $clean);
        $this->assertStringNotContainsString('style=', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
        $this->assertStringNotContainsString('onload', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringNotContainsString('data:text/html', $clean);
    }
}
