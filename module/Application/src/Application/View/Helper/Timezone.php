<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

/**
 * Class Timezone
 * @package Application\View\Helper
 */
class Timezone extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;

    public function __invoke()
    {
        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('partials/timezone');

    }
}
