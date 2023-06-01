<?php

namespace API\V1\Rpc\Tip;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class TipController extends AbstractActionController
{

    public function tipAction()
    {

        $params = $this->bodyParams();

        return new JsonModel([$this->getServiceLocator()->get('wallet')->sendAmount($params['value'], $params['pk'])]);

    }

}
