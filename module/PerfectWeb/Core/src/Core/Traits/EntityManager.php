<?php

namespace PerfectWeb\Core\Traits;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

trait EntityManager
{

    use ServiceLocatorAwareTrait;

    /**
     * @return array|\Doctrine\ORM\EntityManager
     * @throws \Exception
     */
    public function getEntityManager()
    {

        $sl = method_exists($this, 'getServiceLocator') ? $this->getServiceLocator() :
            (method_exists($this, 'getServiceManager') ? $this->getServiceManager() : null);
        $sl = ($sl instanceof HelperPluginManager) ? $sl->getServiceLocator() : $sl;

        if (!$sl) {
            throw new \Exception('The service locator/manager cannot be found !');
        }

        return $sl->get('em');

    }

}