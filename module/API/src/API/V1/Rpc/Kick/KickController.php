<?php
namespace API\V1\Rpc\Kick;

use Eye4web\ZfcUser\Warnings\Entity\Warning;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class KickController extends AbstractActionController
{
    public function kickAction()
    {

        $warning = new Warning();
        $warning->setUser($this->params()->fromRoute('user'));
        $warning->setDate(new \DateTime());
        $warning->setUserBy($this->zfcUserAuthentication()->getIdentity()->getId());
        $warning->setReason('Misbehaving');

        return new JsonModel([
            'status' => $this->getServiceLocator()
                             ->get('Eye4web\ZfcUser\Warnings\Service\WarningsService')
                             ->addWarning($warning)
        ]);
    }
}
