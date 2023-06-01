<?php

namespace PerfectWeb\Core\Listener;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;

class EntityListenerFactory extends DefaultEntityListenerResolver implements AbstractFactoryInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * @inheritdoc
     */
    public function resolve($name)
    {
        return $this->canCreateServiceWithName($this->getServiceLocator(), $name, $name)
            ? $this->createServiceWithName($this->getServiceLocator(), $name, $name)
            : parent::resolve($name);
    }

    /**
     * @inheritdoc
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return class_exists($requestedName);
    }

    /**
     * @inheritdoc
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {

        $service = new $requestedName();

        if ($service instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
            $service->setServiceLocator($serviceLocator);
        }

        return $service;
    }

}