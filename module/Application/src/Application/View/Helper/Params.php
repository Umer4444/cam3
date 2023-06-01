<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Params
 * @package Application\View\Helper
 */
class Params extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    function __invoke()
    {
        return $this;
    }

    /**
     * @param null $param
     * @param null $default
     * @return mixed
     */
    public function fromPost($param = null, $default = null)
    {
        if ($param === null) {
            return $this->getRequest()->getPost($param, $default)->toArray();
        }

        return $this->getRequest()->getPost($param, $default);
    }

    /**
     * @param null $param
     * @param null $default
     * @return array|mixed
     */
    public function fromRoute($param = null, $default = null)
    {
        if ($param === null) {
            return $this->getEvent()->getRouteMatch()->getParams();
        }

        if (!$this->getEvent()->getRouteMatch()) {
            return false;
        }

        return $this->getEvent()->getRouteMatch()->getParam($param, $default);
    }

    /**
     * @param null $param
     * @param null $default
     * @return mixed
     */
    public function fromQuery($param = null, $default = null)
    {
        if ($param === null) {
            return $this->getRequest()->getQuery($param, $default)->toArray();
        }

        return $this->getRequest()->getQuery($param, $default);
    }

    function getRequest()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('Application')->getRequest();
    }

    function getEvent()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('Application')->getMvcEvent();
    }

}