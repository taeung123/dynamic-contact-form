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
}
