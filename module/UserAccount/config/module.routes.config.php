<?php
return array(
    'router' => array(
        'routes' => array(
            'user-account' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/account/information',
                    'defaults' => array(
                        'controller' => 'user-account',
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
                                'controller' => 'user-account',
                                'action' => 'edit-account',
                            ),
                        ),
                    ),
                    'edit-process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/submit',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'user-account',
                                'action' => 'edit-account-process',
                            ),
                        ),
                    ),
                    'edit-profile-process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/profile/submit',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'user-account',
                                'action' => 'edit-profile-process',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);