<?php

namespace PerfectWeb\Core\Twig\Extensions;

class Json extends \Twig_Extension
{

    /**
     * Define Twig filters
     * @example
     * {{ string|json_decode }}
     * {{ string|json_encode }}
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('json_decode', array($this, 'jsonDecode'))
        );
    }

    /**
     * Define Twig functions
     * @example
     * {{ json_decode(string) }}
     * {{ json_encode(string) }}
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'json_decode' => new \Twig_Function_Method($this, 'jsonDecode'),
            'json_encode' => new \Twig_Function_Method($this, 'jsonEncode')
        );
    }

    /**
     * Decode JSON string
     *
     * @param  string $string
     *
     * @return object
     */
    public function jsonDecode($string)
    {
        return json_decode($string);
    }

    /**
     * Encode an object or array to JSON
     *
     * @param  array $array
     *
     * @return string
     */
    public function jsonEncode($array)
    {
        return json_encode($array);
    }

    /** Extension name */
    public function getName()
    {
        return 'json_extension';
    }

}