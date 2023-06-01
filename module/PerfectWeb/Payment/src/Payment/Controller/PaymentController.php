<?php

namespace PerfectWeb\Payment\Controller;

use PerfectWeb\Core\Traits\Ensure;
use PerfectWeb\Payment\Form\Unlock;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class PaymentController
 * @package PerfectWeb\Payment\Controller
 */
class PaymentController extends AbstractActionController
{

    use Ensure;

    public function purchaseAction()
    {

        $hash = $this->getServiceLocator()->get('ViewRenderer')->plugin('crypt')->decrypt(
            $this->params()->fromRoute('hash')
        );
        $url = $this->getServiceLocator()->get('ViewRenderer')->plugin('crypt')->decrypt(
            $this->params()->fromRoute('url')
        );
        list($object, $identity) = explode('::', $hash);

        if ($this->getServiceLocator()->get('wallet')->purchase($object, $identity)) {
            return $this->redirect()->toUrl($url);
        }

        $this->redirect()->toRoute('payment');

    }

    public function unlockAction()
    {

        $hash = $this->getServiceLocator()->get('ViewRenderer')->plugin('crypt')->decrypt(
            $this->params()->fromRoute('hash')
        );
        $url = $this->getServiceLocator()->get('ViewRenderer')->plugin('crypt')->decrypt(
            $this->params()->fromRoute('url')
        );
        list($object, $identity) = explode('::', $hash);

        $form = new Unlock();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if (
                $form->isValid() &&
                $this->ensureObject($object, $identity)->getPassword() == $this->params()->fromPost('password') &&
                $this->getServiceLocator()->get('wallet')->purchase($object, $identity)
            ) {
                return $this->redirect()->toUrl($url);
            }

        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('perfect-web/payment/unlock');

        return $view;

    }

}