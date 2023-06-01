<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Twilio extends \Services_Twilio implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    private $sid = 'ACea2ff76988a2536ede2661687d571889';

    private $token = '48d37212e0c477274cd5b3ed403e788b';

    function __construct()
    {
        return parent::__construct($this->sid, $this->token);
    }

}