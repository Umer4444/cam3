<?php
namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

//use Zend\ServiceManager\ServiceLocatorAwareInterface;
/**
 * Class GlobalVars
 * @package Application\View\Helper
 */
class GlobalVars extends AbstractHelper
{

    /**
     * service locator
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * invoke helper method
     *
     * @return array
     */
    public function __invoke()
    {
        $authService = $this->serviceLocator->get('zfcuser_auth_service');

        $hasIdentity = $authService->hasIdentity();

        if ($hasIdentity) {

            $user = $authService->getIdentity();

        } else {
            $user = null;
        }

        return array(
            'hasIdentity' => $hasIdentity,
            'user' => $user,
        );
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return GlobalVars
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
