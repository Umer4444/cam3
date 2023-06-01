<?php

namespace PerfectWeb\Core\Utils;

class Slug
{

    static function getSlug($string, $delimiter = '-')
    {
        $string = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
        $string = strtolower(trim($string, $delimiter));
        return trim(preg_replace("/[\/_|+ -]+/", $delimiter, $string), $delimiter);
    }

}