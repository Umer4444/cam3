<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Config extends AbstractPlugin
{

    /**
     * @return \Application\View\Helper\Config
     */
    public function __invoke()
    {
        return call_user_func_array(
            $this->getController()->getServiceLocator()->get('ViewHelperManager')->get('cfg'),
            func_get_args()
        );
    }

}