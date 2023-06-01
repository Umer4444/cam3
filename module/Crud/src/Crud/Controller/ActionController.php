<?php

namespace Crud\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Filter\Word\CamelCaseToSeparator;

class ActionController extends AbstractActionController
{

    public function dispatch(Request $request, Response $response = null)
    {

        $dispatched = parent::dispatch($request, $response);
        if ($dispatched instanceof ViewModel) {

            $endTemplate = explode('/', $dispatched->getTemplate());
            $class = get_class($this);
            $dispatched->setTemplate($this->getServiceLocator()->get('crud_template.'.$class.'.'.$endTemplate[2]));

            $crudName = explode('\\', $class);
            end($crudName);
            $crudName = str_replace(['Base', 'Controller'], '', current($crudName));
            $ctd = new CamelCaseToSeparator('-');
            $crudName = strtolower($ctd->filter($crudName));

            $dispatched->setVariable('entity', $crudName);
        }

        return $dispatched;

    }

}