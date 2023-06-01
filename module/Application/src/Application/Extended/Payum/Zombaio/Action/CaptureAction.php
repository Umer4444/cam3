<?php

namespace Application\Extended\Payum\Zombaio\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpPostRedirect;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Extended\Payum\Request\Api\CreateCharge;

class CaptureAction implements ActionInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    public function execute($request)
    {

        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();

        if (!count($model->getDetails())) {

            $config = $this->getServiceLocator()->get('Config')['payment']['zombaio'];

            $amount = number_format($model->getTotalAmount(), 2, '.', '');
            $post = [
                'identifier' => $model->getUser()->getId(),
                'return_url_error' => $request->getToken()->getAfterUrl(),
                'approve_url' => $request->getToken()->getAfterUrl(),
                'decline_url' => $request->getToken()->getAfterUrl(),
                'hide_credits' => 'True',
                'DynAmount_Value' => $amount,
                'DynAmount_Hash' => md5($config['zombaio_gw'] . $amount)
            ];

            throw new HttpPostRedirect(
                'https://secure.zombaio.com/?' . $config['site_id'] . '.' . $config['pricing_id'] . '.ZOM', $post
            );

        }

        $this->payment->execute(new CreateCharge($model));

    }

    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \Application\Entity\Payment;
    }

}