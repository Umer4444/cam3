<?php

namespace PerfectWeb\Core\Utils;

class Protect
{

    static function getUrl($string)
    {
        return '/protected/'.base64_encode($string).substr($string, -4);
    }

}