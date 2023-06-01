<?php

namespace PerfectWeb\Core\Traits;

trait Routable
{

    /**
     * @param null $type
     *
     * @return string
     */
    function getRoute($type = null)
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * @return array
     */
    function getRouteParams()
    {
        return [];
    }

}