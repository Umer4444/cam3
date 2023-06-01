<?php
use Application\Mapper\Injector;

return array(
    'router' => array(
        'routes' => array(
            'store-domain' => array(
                'type' => 'Hostname',
                'options' => array(
                    'route' => ':subdomain.xexposed.com',
                    'defaults' => array(
                        'subdomain' => 'store',
                    ),
                    'constraints' => array(
                        'subdomain' => '(store)',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'magento' => array(
                        'type' => 'Regex',
                        'options' => array(
                            'regex' => '/(?<uri>.*)',
                            'spec' => '/%uri%',
                        ),

                    ),
                ),
            ),
            'rtmp' => array(
                'type' => 'Scheme',
                'options' => array(
                    'scheme' => 'rtmp',
                ),
                'child_routes' => array(
                    'domain' => array(
                        'type' => 'Hostname',
                        'options' => array(
                            'route' => ':subdomain.xexposed.com',
                            'defaults' => array(
                                'subdomain' => 'stream',
                            ),
                        ),
                        'child_routes' => array(
                            'stream' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/stream[/:stream]',
                                ),
                            ),
                            'play' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/play[/:stream]',
                                ),
                            ),
                            'record' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/record[/:stream]',
                                ),
                            ),
                            'hls' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/hls[/:stream]',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'hls' => array(
                'type' => 'Hostname',
                'options' => array(
                    'route' => ':subdomain.xexposed.com',
                    'defaults' => array(
                        'subdomain' => 'stream',
                    ),
                ),
                'child_routes' => array(
                    'stream' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/hls[/:stream]',
                        ),
                    ),
                ),
            ),
            'stream' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/stream',
                    'defaults' => array(
                        'controller' => \Application\Controller\StreamController::class,
                    ),
                ),
                'child_routes' => array(
                    'type' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:action/:'.Injector::USER.'[/:width/:height]',
                            'defaults' => array(
                                'action' => 'live',
                            ),
                            'constraints' => array(
                                'action' => '(live|broadcast)',
                                Injector::USER => '[0-9]+',
                                'width' => '[0-9]+',
                                'height' => '[0-9]+',
                            ),
                        ),
                    ),
                    'config' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/config/:'.Injector::USER.'[/:type][/:camera][/:size]',
                            'defaults' => array(
                                'action' => 'config',
                                'type' => 'broadcast',
                                'size' => 'small',
                                'camera' => 1,
                            ),
                            'constraints' => array(
                                'type' => '(broadcast|record)',
                                'size' => '(small|full)',
                                'camera' => '[0-9]+',
                            ),
                        ),
                    ),
                )
            ),
            'node' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/node/:server/[:game/]',
                     'defaults' => array(
                        'server' => 'notifications',
                     ),
                ),
            ),
            'noop' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/noop',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'noop',
                    ),
                ),
            ),
            'zfcuser' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/account',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'config' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/config[/:action]',
                            'defaults' => array(
                                'controller' => \Application\Controller\ConfigController::class,
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/edit',
                            'defaults' => array(
                                'controller' => 'user-account',
                                'action' => 'edit-account',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/register[/:type]',
                            'defaults' => array(
                                'action' => 'register',
                                'type' => 'user'
                            ),
                            'constraints' => array(
                                'type' => '(performer|user|vip_user|studio)',
                            ),
                        ),
                    ),
                ),
            ),
            'vod' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vod',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'vod'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/category/:category][/page/:page][/user/:'. Injector::USER .']',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action' => 'vodsList',
                            ),
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),

                        ),
                    ),
                ),
            ),
            'news' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/news',
                    'defaults' => array(
                        'controller' => \Application\Controller\NewsController::class,
                        'action' => 'index'
                    ),
                ),
                'child_routes' => [
                    'view' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '[/:'.Injector::NEWS.']',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'view'
                            ),
                        ),
                    ]
                ]
            ),


            'models' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/models',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Performer',
                        'action' => 'list',
                    ),
                ),
            ),
            'blog' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/blog',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Blog',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'view' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:' . Injector::BLOG_SLUG,
                            'defaults' => array(
                                'controller' => 'Application\Controller\Blog',
                                'action' => 'view',
                            ),
                        ),
                    ),
                    'filters' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/category/:categoryId/:category][/tag/:tag][/:page]',
                            'constraints' => array(
                                'page' => '[0-9]*',
                                'id' => '[0-9]*',
                            ),
                        ),
                    ),
                ),
            ),
            'halloffame' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/hall-of-fame',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'hallOfFame',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'view' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:type]',
                        ),
                    ),
                ),
            ),
            'clubs' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/clubs',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'clubs',
                    ),
                ),
            ),
            'activity-append' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/activity-append',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'activityAppend',
                    ),
                ),
            ),
            'presentations' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/presentations',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'presentations',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'presentation' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/presentation/:' . Injector::VIDEO.'/:slug',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action' => 'presentation'
                            )
                        ),
                    ),
                ),
            ),
            'activity' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/activity',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'activity',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'filter' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:filter][/:page]',
                            'constraints' => array(
                                'filter' => '(Blogs|Transfers|SentTransfers)',
                                'page' => '[0-9]+',
                            ),
                        ),
                    )
                )
             ),
            'popularrooms' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/popular-rooms',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'popularRooms',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'popular-rooms' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/page/:page]',
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
            'contests' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/contests',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'contests',
                    ),
                ),
            ),
            'wishlist' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/wishlist',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'wishlist',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'wishlist-list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/item/:id]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action' => 'wishlistList'
                            ),
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
            'phone-list' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/phone/list',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'phoneList',
                    ),
                ),
            ),
            'requests' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/requests',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'requests',
                    ),
                ),
            ),
            'guestbook' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/guestbook',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index', // do a controller for main site as well
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'guestbook-user' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:user'
                        ),
                    )
                )
            ),
        ),
    ),
);