<?php

namespace Application\Controller;

use Application\Mapper\Injector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PlayController extends AbstractActionController
{

    public function indexAction()
    {

    }

    public function chessAction()
    {

        if (!$this->params()->fromRoute(Injector::USER)) {
            return $this->redirect()->toRoute('play');
        }

        $view = new ViewModel();
        $view->setTemplate('application/play/game');

        return $view;

    }

    public function ticTacToeAction()
    {

        if (!$this->params()->fromRoute(Injector::USER)) {
            return $this->redirect()->toRoute('play');
        }

        $view = new ViewModel();
        $view->setTemplate('application/play/game');

        return $view;

    }

    public function checkersAction()
    {

        if (!$this->params()->fromRoute(Injector::USER)) {
            return $this->redirect()->toRoute('play');
        }

        $view = new ViewModel();
        $view->setTemplate('application/play/game');

        return $view;

    }

}