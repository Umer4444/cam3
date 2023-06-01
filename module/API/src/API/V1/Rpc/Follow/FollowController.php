<?php
namespace API\V1\Rpc\Follow;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use PerfectWeb\Core\Entity\ResourceValue;

class FollowController extends AbstractActionController
{
    public function followAction()
    {

        $settings = [];
        foreach ($this->bodyParams()['value'] as $value) {

            $setting = new ResourceValue();
            $setting->setValue($value);
            $setting->setReferringTo($this->bodyParam('pk'));

            $settings[] = $setting;

        }

        $this->getServiceLocator()->get('cfg')->setConfigValue('performer', $settings, 'follow');

        return new JsonModel([]);

    }
}
