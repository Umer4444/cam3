<?php

namespace Application\Extended\Zend\Uri;

use Zend\Uri\Http;

/**
 * @inheritdoc
 */
class Rtmp extends Http
{

    /**
     * @inheritdoc
     */
    function __construct($uri = null)
    {
        parent::__construct($uri);
        self::$validSchemes[] = 'rtmp';
        self::$defaultPorts['rtmp'] = 1935;
    }

}
