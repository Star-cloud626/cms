<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Format helper
 */
class FormatHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    /**
     * Trim paragraph to specified size
     *
     * @param string|null $text Text to trim
     * @param int $size Maximum size
     * @return string
     */
    public function trimParagraph(?string $text, int $size = 300): string
    {
        if (empty($text)) {
            return '';
        }

        if (strlen($text) > $size) {
            return rtrim(substr($text, 0, $size), 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz.') . '...';
        }

        return $text;
    }
}

