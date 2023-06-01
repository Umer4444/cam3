<?php

namespace Application\Extended\Payum\Epoch\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class CaptureAction implements ActionInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    public function execute($request)
    {

        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();

        'https://api.ccbill.com/wap-frontflex/flexforms/1339635d-1961-45a0-aeb2-7b8b995be175?clientSubacc=0000&initialPrice=3587&initialPeriod=1&currencyCode=840&formDigest=';

    }

    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \Application\Entity\Payment;
    }

}