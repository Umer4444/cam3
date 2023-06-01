<?php

use Application\Controller\UserController;
use ZfcUser\Controller\RedirectCallback;
use Zend\Mvc\Controller\ControllerManager;

return array(
    'controllers' => array(
        'invokables' => array(
            'payment' => 'Application\Controller\PaymentController',
            'zf1' => 'Application\Zf1Compat\Controller\Zf1Controller',
        ),
        'factories' => array(
            'zfcuser' => function ($controllerManager) {
                /* @var ControllerManager $controllerManager */
                $serviceManager = $controllerManager->getServiceLocator();

                /* @var RedirectCallback $redirectCallback */
                $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');

                /* @var UserController $controller */
                $controller = new UserController($redirectCallback);

                return $controller;
            },
        ),
    ),
);