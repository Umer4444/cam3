<?php

namespace Crud\Controller;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CrudControllerFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (
            strpos($requestedName, __NAMESPACE__) !== false &&
            (
                class_exists($requestedName.'Controller') ||
                class_exists(str_replace("Crud\\Controller\\", "Crud\\Controller\\Base\\Base", $requestedName).'Controller')
            )
        ){
            return true;
        }

        return false;
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {

        if (class_exists($requestedName.'Controller')) {
            $class = $requestedName.'Controller';
        }
        else {
            $class = str_replace("Crud\\Controller\\", "Crud\\Controller\\Base\\Base", $requestedName).'Controller';
        }


        return new $class;

    }

}