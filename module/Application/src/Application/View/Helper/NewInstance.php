<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class NewInstance extends AbstractHelper
{

    public function __invoke($instance, $parameters = null)
    {

        if (strpos($instance, '::') !== false) {
            return forward_static_call_array(explode('::', $instance), $parameters);
        }

        return !is_null($parameters) ? new $instance($parameters) : new $instance;
    }

}