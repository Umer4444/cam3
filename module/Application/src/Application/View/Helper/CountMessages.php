<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * Class CountMessages
 * @package Application\View\Helper
 */
class CountMessages extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * ViewHelper for counting notifications!
     *
     * @param null $id
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __invoke($id = null)
    {

        if (is_null($id)) {

            $serviceLocator = $this->getServiceLocator()->getServiceLocator();
            $auth = $serviceLocator->get('zfcuser_auth_service');
            if ($auth->hasIdentity()) {
                return $this->getServiceLocator()->getServiceLocator()->get('messages')->countMessages($auth->getIdentity()->getId());
            } else {

                throw new \Exception
                ('You must provide an id if the user you are getting the messages for is not logged in!');
            }

        } else {

            return $this->getServiceLocator()->getServiceLocator()->get('messages')->countMessages($id);

        }

    }

} 