<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class User extends AbstractPlugin
{

    /**
     * @return \Application\View\Helper\User
     */
    public function __invoke()
    {
        return call_user_func_array(
            $this->getController()->getServiceLocator()->get('ViewHelperManager')->get('user'),
            func_get_args()
        );
    }

}