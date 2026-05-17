<?php

namespace Twizzle\PartnerItems\utils;

use pocketmine\utils\TextFormat;

class TextUtils {

    public static function colorize(string $text): string {
        return TextFormat::colorize($text);
    }

    public static function replace(string $text, array $pairs): string {
        foreach ($pairs as $key => $value) {
            $text = str_replace("{" . $key . "}", (string)$value, $text);
        }
        return $text;
    }
}
