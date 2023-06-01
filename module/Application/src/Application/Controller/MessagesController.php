<?php
/**
 * Created by PhpStorm.
 * User: userws5
 * Date: 10.03.2015
 * Time: 16:52
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Application\Entity\Message;

class MessagesController extends AbstractActionController
{
    public function composeAction()
    {
        $sender = $this->params()->fromRoute(\Application\Mapper\Injector::USER);
        if (
            $this->zfcUserAuthentication()->hasIdentity() &&
            (
                $sender && $sender != $this->zfcUserAuthentication()->getIdentity()->getId()
            )
        )
        {
            throw new \Exception('You should not be here');
        }

        return new ViewModel(array('form' => $this->getServiceLocator()->get('Application\Form\Messages')));
    }

    public function messagesAction()
    {
        return new ViewModel(array('messageAction' => $this->params()->fromRoute('type')));
    }

    public function viewAction()
    {
    }

} 