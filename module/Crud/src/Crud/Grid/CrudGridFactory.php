<?php

namespace Crud\Grid;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CrudGridFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (
            strpos($requestedName, __NAMESPACE__) !== false &&
            (
                class_exists($requestedName) ||
                class_exists(str_replace("Crud\\Grid\\", "Crud\\Grid\\BaseGrid\\Base", $requestedName))
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
            $class = str_replace("Crud\\Grid\\", "Crud\\Grid\\BaseGrid\\Base", $requestedName);
        }

        return new $class;

    }

}