<?php

namespace VCComponent\Laravel\ConfigContact\Traits;

use Illuminate\Support\Str;

trait Helpers
{

    public function changeLabelToSlug($string)
    {
        return str_replace("-", "_", Str::slug($string));
    }

    public function changeKey($string)
    {
        if (str_contains($string, ":")) {
            $pos = strpos($string, ":");
            return substr($string, 0, $pos);
        }

        return $string;
    }

    public function removeSpecialCharacter($string)
    {
        $string  = preg_replace('/[^\p{L}\p{N}\s]/u', '', strval($string));
        $search  = array('ă', 'â', 'đ', 'ê', 'ơ', 'ô', 'ư', 'Â', 'Ă', 'Đ', 'Ê', 'Ơ', 'Ô', 'Ư');
        $replace = array('a', 'a', 'd', 'e', 'o', 'o', 'u', 'a', 'a', 'd', 'e', 'o', 'o', 'u');

        return str_replace($search, $replace, $string);
    }
    public function removeSpaceOfString($string)
    {
        $new_string = trim(preg_replace('/\s+/', ' ', $string));
        return $new_string;
    }
}
