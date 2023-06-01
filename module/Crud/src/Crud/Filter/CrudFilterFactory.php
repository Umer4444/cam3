<?php

namespace Crud\Filter;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CrudFilterFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (
            strpos($requestedName, __NAMESPACE__) !== false &&
            (
                class_exists($requestedName) ||
                class_exists(str_replace("Crud\\Filter\\", "Crud\\Filter\\BaseFilter\\Base", $requestedName))
            )
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
            $class = str_replace("Crud\\Filter\\", "Crud\\Filter\\BaseFilter\\Base", $requestedName);
        }

        return new $class;

    }

}