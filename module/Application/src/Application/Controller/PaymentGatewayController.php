<?php

namespace Application\Controller;

use PerfectWeb\Payment\Controller\PaymentController;

/**
* Class PaymentGatewayController
* @package Application\Controller
*/
class PaymentGatewayController extends PaymentController
{

    public function indexAction()
    {

        $em = $this->getServiceLocator()->get('em');

        if ($this->getRequest()->isPost()) {

            $storage = $this->getServiceLocator()->get('payum')->getStorage('Application\Entity\Payment');

            /** @var $package \Application\Entity\Package */
            $package = $em->find('Application\Entity\Package', (int)$this->params()->fromPost('package'));
            $paymentMethod = $em->find('Application\Entity\PaymentMethod', (int)$this->params()->fromPost('paymentMethod'));

            if (!$package || !$paymentMethod) {
                return  $this->redirect()->toRoute('payment');
            }

            /** @var $details \Application\Entity\Payment */
            $details = $storage->create();
            $details->setDescription($package->getName());
            $details->setTotalAmount($package->getAmount());
            $details->setClientEmail($this->zfcUserAuthentication()->getIdentity()->getEmail());
            $details->setUser($this->zfcUserAuthentication()->getIdentity());
            $storage->update($details);

            $captureToken = $this->getServiceLocator()
                                 ->get('payum.security.token_factory')
                                 ->createCaptureToken($paymentMethod->getConfigName(), $details, 'payment/done');

            return $this->redirect()->toUrl($captureToken->getTargetUrl());

        }

        return [
            'paymentMethods' => $em->getRepository('Application\Entity\PaymentMethod')->findAll(),
            'packages' => $em->getRepository('Application\Entity\Package')->findAll()
        ];


    }

    public function doneAction()
    {}

    public function errorAction()
    {}

}
