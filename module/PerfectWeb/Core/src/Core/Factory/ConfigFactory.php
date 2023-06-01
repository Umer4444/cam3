<?php

namespace PerfectWeb\Core\Factory;

use PerfectWeb\Core\Entity\Role;
use PerfectWeb\Core\Traits;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfigFactory implements AbstractFactoryInterface, ServiceLocatorAwareInterface
{

    use Traits\Ensure;


    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->setServiceLocator($serviceLocator);
        return $requestedName == 'cfg' || strpos($requestedName, '.cfg') || strpos($requestedName, 'cfg.') !== false;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {

        $context = $requestedName;

        if (substr_count($requestedName, '.') == 1) {

            list($cfg, $userId) = explode('.', $requestedName);

            if (is_numeric($userId)) {
                $user = $this->ensureUser($userId);
                $context = $user->getRole().'.cfg';
            }

        }
        elseif (substr_count($requestedName, '.') == 2) {

            list($role, $cfg, $userId) = explode('.', $requestedName);

            if (!is_numeric($userId)) {
                throw new \Exception('The requested user id context is not valid !');
            }

        }
        else {

            $authService = $serviceLocator->get('zfcuser_auth_service');
            $userId = null;
            $matchedRoute = $serviceLocator->get('Router')->match($serviceLocator->get('Request'));
            $routeName = !$matchedRoute ?: $matchedRoute->getMatchedRouteName();

            $isSuperUser  = $authService->hasIdentity() &&
                in_array(
                    $authService->getIdentity()
                                ->getRole(), [Role::ADMIN, Role::SUPER_ADMIN]
                );
            $isAdminRoute = strpos($routeName, 'zfcadmin') !== false;
            $isApiRoute = strpos($routeName, 'api.') !== false;

            // make everyone have user context on frontend
            if (!$isAdminRoute && !$isApiRoute && $requestedName != 'site.cfg') {
                $context = 'user.cfg';
            }

            if ($authService->hasIdentity()) {

                $userId = $authService->getIdentity()->getId();

                if (($isAdminRoute || $isApiRoute ) && $requestedName != 'site.cfg') {
                    $context = $authService->getIdentity()->getRole() . '.cfg';
                }

            }

            // admins have only site.cfg on backend
            if ($isAdminRoute && $isSuperUser) {
                $context = 'site.cfg';
                $userId  = null; // make sure there is no userId set for site context
            }

        }

        $service = new \PerfectWeb\Core\Service\ConfigAdmin($context, $userId);
        $service->setIsPreviewEnabled(false);

        return $service;

    }

}