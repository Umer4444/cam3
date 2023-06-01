<?php

namespace Application\Extended\Doctrine\Filter;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractFilterFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {

        if ($requestedName == 'application.doctrine.filter.active') die('here');
        if (
            class_exists($requestedName) ||
            class_exists(str_replace('Application\Extended\Doctrine\Filter', "Crud\\Grid\\BaseGrid\\Base",
            $requestedName))
        ){
            return true;
        }

        return false;
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {

        if (class_exists($requestedName)) {
            $class = $requestedName;
        }
        else {
            $class = str_replace("Crud\\Grid\\", "Crud\\Grid\\BaseGrid\\Base", $requestedName);
        }

        return new $class;

    }

}