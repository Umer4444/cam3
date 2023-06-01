<?php

namespace Application\View\Helper;

use Application\Mapper\Injector;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use PerfectWeb\Core\Traits;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Url;

class User extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\User;
    use Traits\EntityManager;

    public function __invoke($user = null)
    {

        $application = $this->getServiceLocator()->getServiceLocator()->get('Application');

        // get the user from view model if exists
        if (!$user && $this->getView()->viewModel()->getRoot()->getVariable('user') instanceof
             \Application\Entity\User) {
            $user = $this->getView()->viewModel()->getRoot()->getVariable('user');
        }

        // get the id of the user from several places if available
        elseif (
            is_numeric($user) ||
            (
                $application->getMvcEvent()->getRouteMatch() &&
                $userId = $application->getMvcEvent()->getRouteMatch()->getParam(Injector::USER)
            ) ||
            ($userId = $application->getRequest()->getQuery()->get(Injector::USER))
        ) {
            $user = $this->getEntityManager()->find('Application\Entity\User', $user ?: $userId );
        }
        elseif (
            !($user instanceof \Application\Entity\User) &&
            $this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service')->hasIdentity()
        ) {
            $user = $this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();
        }

        if (!($user instanceof \Application\Entity\User)) {
            throw new \Exception('You must specify a user first !'.
                 (getenv('APPLICATION_ENV') != 'development' ?'':' provided: '.print_r($user, true))
            );
        }

        $this->setUser($user);

        return $this;

    }

    /**
     * @return \Application\Entity\User
     */
    public function getLoggedUser()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();
    }

    function __call($method, $arguments)
    {

        $forwardInChain = $this->getServiceLocator()->get($method);

        if ($forwardInChain instanceof Url) {

            $arguments[1] = array_merge(
                [
                    Injector::USER => $this->getUser()->getId(),
                    'name' => $this->getUser()->getDisplayName().'--'.$this->getUser()->getFirstName(),
                ],
                is_array($arguments[1]) ? $arguments[1] : []
            );
            return call_user_func_array(array($forwardInChain, '__invoke'), $arguments);
        }

        return
            method_exists($forwardInChain, 'setUser') ?
                $forwardInChain->setUser($this->getUser()) :
                (
                    method_exists($forwardInChain, 'setEntity') ?
                        $forwardInChain->setEntity($this->getUser()) :
                        $forwardInChain
                );
    }

    /**
     * @return string
     */
    function getContextInnerTag()
    {

        if (!$this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service')->hasIdentity()) {
            return '';
        }

        return ' data-type="context-menu" data-user="'.$this->getUser()->getId().'" ';

    }

}