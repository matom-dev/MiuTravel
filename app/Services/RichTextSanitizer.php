<?php

namespace App\Services;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h2><h3><h4><a><img><figure><figcaption><table><thead><tbody><tr><th><td><span><div>';

    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $html = preg_replace('#<(script|style|iframe|object|embed|form|input|button)[^>]*>.*?</\1>#is', '', $html);
        $html = strip_tags($html, self::ALLOWED_TAGS);

        // Drop event handlers and dangerous URI schemes while keeping editor HTML usable.
        $html = preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/iu', '', $html);
        $html = preg_replace('/\s+on[a-z]+\s*=\s*[^\s>]+/iu', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:.*?\2/iu', '$1="#"', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*data:text\/html.*?\2/iu', '$1="#"', $html);

        return trim($html);
    }
}
