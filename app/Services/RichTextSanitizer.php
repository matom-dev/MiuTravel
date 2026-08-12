<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\File;

class RichTextSanitizer
{
    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        return trim($this->purifier()->purify($html));
    }

    private function purifier(): HTMLPurifier
    {
        static $purifier;

        if ($purifier instanceof HTMLPurifier) {
            return $purifier;
        }

        $cachePath = storage_path('framework/cache/htmlpurifier');
        File::ensureDirectoryExists($cachePath);

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('Cache.SerializerPath', $cachePath);
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
        ]);
        $config->set('HTML.Allowed', implode(',', [
            'p',
            'br',
            'strong',
            'b',
            'em',
            'i',
            'u',
            'ul',
            'ol',
            'li',
            'blockquote',
            'h2',
            'h3',
            'h4',
            'a[href|title|target|rel]',
            'img[src|alt|title|width|height]',
            'table',
            'thead',
            'tbody',
            'tr',
            'th',
            'td',
            'span',
            'div',
        ]));
        $config->set('Attr.AllowedFrameTargets', ['_blank']);

        return $purifier = new HTMLPurifier($config);
    }
}
