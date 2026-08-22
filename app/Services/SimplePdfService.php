<?php

namespace App\Services;

class SimplePdfService
{
    private const PAGE_WIDTH = 595;
    private const PAGE_HEIGHT = 842;
    private const LEFT = 48;
    private const TOP = 790;
    private const LINE_HEIGHT = 16;

    public function make(array $lines, string $title = 'Miu Travel'): string
    {
        $content = [];
        $y = self::TOP;

        $content[] = 'BT /F1 18 Tf ' . self::LEFT . ' ' . $y . ' Td (' . $this->escape($title) . ') Tj ET';
        $y -= 28;
        $content[] = 'BT /F1 10 Tf ' . self::LEFT . ' ' . $y . ' Td (' . $this->escape('Generated at ' . now()->format('Y-m-d H:i:s')) . ') Tj ET';
        $y -= 26;

        foreach ($lines as $line) {
            foreach ($this->wrap($this->plain($line), 92) as $wrappedLine) {
                if ($y < 56) {
                    break 2;
                }

                $content[] = 'BT /F1 11 Tf ' . self::LEFT . ' ' . $y . ' Td (' . $this->escape($wrappedLine) . ') Tj ET';
                $y -= self::LINE_HEIGHT;
            }
        }

        return $this->document(implode("\n", $content));
    }

    private function document(string $pageContent): string
    {
        $objects = [
            '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
            '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
            '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . self::PAGE_WIDTH . ' ' . self::PAGE_HEIGHT . '] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj',
            '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj',
            '5 0 obj << /Length ' . strlen($pageContent) . " >> stream\n" . $pageContent . "\nendstream endobj",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xrefOffset . "\n%%EOF";

        return $pdf;
    }

    private function wrap(string $line, int $length): array
    {
        if ($line === '') {
            return [''];
        }

        return explode("\n", wordwrap($line, $length, "\n", true));
    }

    private function plain(string $value): string
    {
        $plain = trim(strip_tags($value));
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $plain);

        return $converted !== false ? $converted : $plain;
    }

    private function escape(string $value): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $value);
    }
}
