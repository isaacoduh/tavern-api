<?php

namespace App\Helpers\Utils;

use Illuminate\Support\Str;

class StringUtil
{
    static function generateRandomString($length = 16)
    {
        return Str::random($length);
    }

    static function generateSlugFromText($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}