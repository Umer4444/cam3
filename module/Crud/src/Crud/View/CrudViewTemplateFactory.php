<?php

namespace Crud\View;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Filter\Word\CamelCaseToSeparator;

class CrudViewTemplateFactory implements AbstractFactoryInterface
{

    private $template = null;

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {

        $parts = explode('.', $requestedName);
        if ($parts[0] != 'crud_template') {
            return false;
        }

        $viewFolder = dirname(__DIR__).'/../../view/crud/';
        $crudName = explode('\\', $parts[1]);
        end($crudName);
        $crudName = str_replace(['Base', 'Controller'], '', current($crudName));
        $ctd = new CamelCaseToSeparator('-');
        $crudName = strtolower($ctd->filter($crudName));

        $file = realpath($viewFolder.$crudName.'/'.$parts[2].'.phtml');
        if (file_exists($file)){
            $this->template = 'crud/'.$crudName.'/'.$parts[2];
            return true;
        }

        $file = realpath($viewFolder.'default/'.$parts[2].'.phtml');
        if (file_exists($file)){
            $this->template = 'crud/default/'.$parts[2];
            return true;
        }

        return false;
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
       return $this->template;

    }

}