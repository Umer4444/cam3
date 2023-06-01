<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Route
 *
 * @package Application\View\Helper
 */
class Route extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * @param null $paramsFrom
     * @param null $param
     * @return array
     */
    public function __invoke($paramsFrom = null, $param = null)
    {
        
        $routeMatch = $this->getServiceLocator()->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();

        if (!method_exists($routeMatch, 'getMatchedRouteName')) {
            return null;
        }

        return $routeMatch->getMatchedRouteName();

    }

}