<?php

use PerfectWeb\Core\Entity\Role;

return array(
    'doctrine' => array(
        'driver' => array(
            'PerfectWebCoreEntityDriver' => array(
                'paths' => array(
                    __DIR__ . '/../src/Payment/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'PerfectWeb\Payment\Entity' => 'PerfectWebCoreEntityDriver',
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'payment/unlock' => __DIR__ . '/../view/perfect-web/payment/unlock.twig',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            \PerfectWeb\Payment\Controller\PaymentController::class => \PerfectWeb\Payment\Controller\PaymentController::class,
        ),
    ),
    'router' => array(
        'routes' => array(
            'payment' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/payment',
                    'defaults' => array(
                        'controller' => \PerfectWeb\Payment\Controller\PaymentController::class,
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'purchase' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/purchase/:hash/:url',
                            'defaults' => array(
                                'action' => 'purchase',
                            ),
                        ),
                    ),
                    'unlock' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/unlock/:hash/:url',
                            'defaults' => array(
                                'action' => 'unlock',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            \BjyAuthorize\Guard\Controller::class => array(
                [
                    'controller' => \PerfectWeb\Payment\Controller\PaymentController::class,
                    'roles' => Role::getLoggedInRoles()
                ],
            )
        )
    )

);
