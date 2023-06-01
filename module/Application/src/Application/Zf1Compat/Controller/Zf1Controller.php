<?php

namespace Application\Zf1Compat\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class Zf1Controller
 * @package Application\Controller
 */
class Zf1Controller extends AbstractActionController
{

    public function indexAction()
    {

        $view = new ViewModel();
        $view->setTemplate('zf1/fallback');

        return $view;

    }

    public function processAction()
    {

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('zf1/fallback')->setTerminal(true);

        return $view;

    }

    public function zf2bareAction()
    {

        $this->layout('layout/bare');

        $view = new ViewModel();
        $view->setTemplate('zf1/fallback');

        return $view;

    }


}