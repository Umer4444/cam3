<?php
use Application\Mapper\Injector;

return array(
    'router' => array(
        'routes' => array(
            'albums' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/albums',
                    'defaults' => array(
                        'controller' => 'Images\Controller\Index',
                        'action' => 'albums'
                    ) ,
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'album' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:'.Injector::ALBUM.'/:slug',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'album'
                            )

                        ),
                    ),
                ),
            ),
//            'contact' => array(
//                'type' => 'Literal',
//                'option' => array(
//                    'route' => '/contact/process',
//                    'defaults' => array(
//                        'controller' => 'Solo\Controller\ContactController',
//                        'action' => 'process'
//                    ),
//                ),
//            ),
            'images' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/images',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'image' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:'.Injector::IMAGE.'/:slug',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'image'
                            )
                        ),
                    ),
                ),
            ),
            'videos' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/videos',
                    'defaults' => array(
                        'controller' => 'Solo\Controller\Index',
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
                            ),
                        ),
                    ),
                ),
            ),
            'performer' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/about',
                    'defaults' => array(
                        'controller' => 'Solo\Controller\Index',
                        'action' => 'about'
                    ),
                ),
                'may_terminate' => true,
            ),
            'solo' => array(
                'type' => 'Zend\Mvc\Router\Http\Hostname',
                'options' => array(
                    'route' => $_SERVER['HTTP_HOST'],
                    'defaults' => array(
                        'controller' => 'Solo\Controller\Index',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'purchase' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/buy',
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'purchase'
                            ),
                        ),
                    ),
                    'social' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/social',
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'social'
                            ),
                        ),
                    ),
                    'guestbook' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/guestbook',
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'guestbook'
                            ),
                        ),
                    ),
                    'scheduleAjax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/scheduleAjax',
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'scheduleAjax'
                            )
                        )
                    ),
                    'pledge' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/pledge[/:page[/:slug]]',
                            'constraints' => array(
                                'page' => 'page-[0-9]*',

                            ),
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'pledge',
                            ),
                        ),
                    ),
                    'friends' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/friends[/:page[/:slug]]',
                            'constraints' => array(
                                'page' => 'page-[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'friends',
                            ),
                        ),
                    ),
                    'usc' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/page[/:page]',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'staticPages',
                            ),
                        ),
                    ),
                    'photos' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/photos[/:id][/:imageSlug]',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'photos',
                            ),
                        ),
                    ),
                    'deleteMessage' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete/message',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'deleteMessage',

                            ),
                        ),
                    ),
                    'live' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/live',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Performer',
                                'action' => 'watch'

                            ),
                        ),
                    ),
                    'archive' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/archive',
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'archive',

                            ),
                        ),
                    ),
                    'home' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/',
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'profile' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/profile[/:username]',
                            'constraints' => array(),
                            'defaults' => array(
                                'controller' => 'Solo\Controller\Index',
                                'action' => 'profile',
                            ),
                        ),
                    ),
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
                                        'controller' => 'zfcuser',
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
                ),
            ),
        ),
    ),
);
