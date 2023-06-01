<?php

namespace Crud\Form;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CrudFormFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (
            strpos($requestedName, __NAMESPACE__) !== false &&
            (
                class_exists($requestedName) ||
                class_exists(str_replace("Crud\\Form\\", "Crud\\Form\\BaseForm\\Base", $requestedName))
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
            $class = str_replace("Crud\\Form\\", "Crud\\Form\\BaseForm\\Base", $requestedName);
        }

        $class = new $class($locator);

        return $class;

    }

}