<?php

namespace PerfectWeb\Core\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

class Object extends AbstractHelper implements ServiceLocatorAwareInterface
{

    const ROUTE_TYPE_VIEW = 'view';

    use Traits\Entity;
    use ServiceLocatorAwareTrait;

    /**
     * @param $entity
     *
     * @return $this
     */
    public function __invoke($entity)
    {
        $this->setEntity($entity);
        return $this;
    }

    /**
     * @param null $route
     * @param string $type
     *
     * @return mixed
     */
    function toUrl($route = null, $type = self::ROUTE_TYPE_VIEW)
    {

        if (!in_array(\PerfectWeb\Core\Interfaces\Routable::class, class_implements($this->getEntity()))) {
            throw new \LogicException('The object does not implement the routable interface !');
        }

        $route = $route ?: $this->getEntity()->getRoute($type);
        $params = $this->getEntity()->getRouteParams();

        return $this->getServiceLocator()->get('url')->__invoke($route, $params);
    }


}