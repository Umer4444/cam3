<?php
namespace Application\Extended\Payum\Zombaio\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if ($model['error']) {
            $request->markFailed();

            return;
        }

        if (false == $model['card']) {
            $request->markNew();

            return;
        }

        // means we have only received a stripe token but have not done a payment.
        if (is_string($model['card'])) {
            $request->markPending();

            return;
        }

        if (is_array($model['card']) && $model['captured'] && $model['paid']) {
            $request->markCaptured();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
