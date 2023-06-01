<?php
use Application\Mapper\Injector;

return array(
    'router' => array(
        'routes' => array(
            'videos' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/videos',
                    'defaults' => array(
                        'controller' => 'Videos\Controller\Videos',
                        'action' => 'videos',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'video' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/video/:' . Injector::VIDEO.'/:slug',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'video'
                            )
                        ),
                    ),
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/page/:page][/user/:'. Injector::USER .']',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'videosList',
                            ),
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                        ),
                    )
                ),
            ),
            'clips' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/clips',
                    'defaults' => array(
                        'controller' => 'Videos\Controller\Videos',
                        'action' => 'clips'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'clip' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/clip/:' . Injector::VIDEO.'/:slug',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'clip'
                            )
                        ),
                    ),
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/page/:page][/user/:'. Injector::USER .']',
                            'defaults' => array(
                                'action' => 'clipsList',
                            ),
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
            'cams' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cams',
                    'defaults' => array(
                        'controller' => 'Videos\Controller\Videos',
                        'action' => 'cams'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'cam' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/cam/:' . Injector::VIDEO.'/:slug',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'cam'
                            )
                        ),
                    ),
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/page/:page][/user/:'. Injector::USER .']',
                            'defaults' => array(
                                'action' => 'camsList',
                            ),
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
            'vods' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vods',
                    'defaults' => array(
                        'controller' => 'Videos\Controller\Videos',
                        'action' => 'vods'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'vod' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/vod/:' . Injector::VIDEO.'/:slug',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'vod'
                            )
                        ),
                    ),
                    //just modified route check history to see changes
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/category/:user][/page/:page][/category/:'. Injector::CATEGORY .']',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'vodsList',
                            ),
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                        ),
                    )
                ),
            ),
            'premiers' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/premiers',
                    'defaults' => array(
                        'controller' => 'Videos\Controller\Videos',
                        'action' => 'premiers'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'premiere' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/premiere/:' . Injector::VIDEO.'/:slug',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'premiere'
                            )
                        ),
                    ),
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/page/:page][/user/:'. Injector::USER .']',
                            'defaults' => array(
                                'controller' => 'Videos\Controller\Videos',
                                'action' => 'premiersList',
                            ),
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                        ),
                    )
                ),
            ),
        ),
    ),
);
