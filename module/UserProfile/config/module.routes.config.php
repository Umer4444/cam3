<?php

use Application\Mapper\Injector;

return array(
    'router' => array(
        'routes' => array(
            'user-profile' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/account/profile',
                    'defaults' => array(
                        'controller' => 'user-profile',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit',
                            'constraints' => array(),
                            'defaults' => array(
                                'action' => 'editProfile',
                            ),
                        ),
                    ),
                    'edit-process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/submit',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'user-profile',
                                'action' => 'edit-profile-process',
                            ),
                        ),
                    ),
                ),
            ),
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'UserProfile\Controller\User',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'profile' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:'.Injector::USER.'/:name/:action[/:filter][/:page][/:type[/:token]]',
                            'defaults' => array(
                                'controller' => 'UserProfile\Controller\User',
                                'action' => 'profile'
                            ),
                            'constraints' => array(
                                'token' => '[0-9]+',
                                'filter' => '(Blogs|Transfers|SentTransfers)',
                                'page' => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);