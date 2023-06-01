<?php

use Application\Mapper\Injector;
use PerfectWeb\Core\Utils\Status;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'payment' => array(
                'options' => array(
                    'defaults' => array(
                        'controller' => 'Application\Controller\PaymentGateway',
                    ),
                ),
                'child_routes' => array(
                    'done' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/done[/:payum_token]',
                            'constraints' => array(
                                'payum_token' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'action' => 'done',
                            ),
                        ),
                    ),
                    'error' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/error[/:payum_token]',
                            'constraints' => array(
                                'payum_token' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'action' => 'error',
                            ),
                        ),
                    ),
                ),
            ),
            'main-live' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/live[/:filter]',
                    'constraints' => array(
                        'filter' => 'online|normal|all',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'live',

                    ),
                ),
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'manage' => array(
                        'child_routes' => array(
                            'videos' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' =>'/videos[/:selected_filter][/:selected_filter_type][/page/:page]',
                                    'constraints' => array(
                                        'page' => '[0-9]*',
                                        'selected_filter' => 'pending|approved|denied|all',
                                        'selected_filter_type' => 'all|profile|default',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Videos',
                                        'action' => 'index',
                                        'selected_filter' => 'all',
                                        'selected_filter_type' => 'all',
                                        'page' => '1',
                                    ),
                                ),
                            ),
                            'profile-page' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/profile-page',
                                    'defaults' => array(
                                        'controller' => \Application\Controller\Admin\PerformerAdminController::class,
                                        'action' => 'manageProfilePage',
                                    ),
                                ),
                            ),
                            'change-status' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/change',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                                    ),
                                    'defaults' => array(
                                        'controller' => 'videos',
                                        'action' => 'changeStatus'
                                    ),
                                ),
                            ),
                            'set-video' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/set-video',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'videos',
                                        'action' => 'setVideo'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'store' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:proxy]store',
                            'defaults' => array(
                                'controller' => \Application\Controller\StoreController::class,
                                'proxy' => ''
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
                        )
                    ),
                    'config' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/config[/:action]',
                            'defaults' => array(
                                'controller' => \Application\Controller\ConfigController::class,
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'multiple' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '[/:group]',
                                    'defaults' => array(
                                        'action' => 'multiple',
                                    ),
                                ),

                            ),
                        )
                    ),
                    'record' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/record[/:type]',
                            'defaults' => array(
                                'controller' => \Application\Controller\Admin\PerformerAdminController::class,
                                'action' => 'record',
                            ),
                        ),
                    ),
                    'broadcast' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/broadcast',
                            'defaults' => array(
                                'controller' => \Application\Controller\Admin\PerformerAdminController::class,
                                'action' => 'broadcast',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'type' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:type/:session[/:starter]',
                                    'constraints' => array(
                                        'type' => 'private',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'friends' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/friends/:status',
                            'constraints' => array(
                                'status' => Status::ACTIVE.'|'.Status::PENDING.'|'.Status::REJECTED,
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Friends',
                                'action' => 'list',
                                'status' => Status::ACTIVE
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'change-position' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/updatePosition',
                                    'defaults' => array(
                                        'action' => 'changePosition'
                                    ),
                                ),
                            ),
                            'change-status' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/updateStatus',
                                    'defaults' => array(
                                        'action' => 'changeStatus'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'model-statistics' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/performer-statistics',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Performer',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'ajax' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/ajax',
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Performer',
                                        'action' => 'tableAjax'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'table-ajax' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/tableAjax',
                            'defaults' => array(
                                'controller' => 'reviews',
                                'action' => 'tableAjax',
                            ),
                        ),
                        'may_terminate' => true

                    ),
                    'table-ajax-comments' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/tableAjaxComments',
                            'defaults' => array(
                                'controller' => 'comments',
                                'action' => 'tableAjax',
                            ),
                        ),
                        'may_terminate' => true

                    ),
                    'manage-photos' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/performer/photos[/:selected_filter][/page/:page]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '[0-9]*',
                                'selected_filter' => 'pending|approved|denied|all',
                            ),
                            'defaults' => array(
                                'controller' => 'photos',
                                'action' => 'index',
                                'selected_filter' => 'all',
                                'page' => '1',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'change-status' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/change',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'photos',
                                        'action' => 'changeStatus',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'manage-comments' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/performer/comments',
                            'defaults' => array(
                                'controller' => 'comments',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'change-status' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/change',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'comments',
                                        //'action' => 'changeStatus',
                                        'action' => 'updateComment'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'manage-reviews' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/performer/reviews',
                            'defaults' => array(
                                'controller' => 'reviews',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'change-status' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/change',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                                    ),
                                    'defaults' => array(
                                        'controller' => 'reviews',
                                        'action' => 'changeStatus'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'notifications' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/notifications',
                            'defaults' => array(
                                'controller' => 'notifications',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'type' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/[:action]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                ),
                            ),
                        ),
                    ),
                )
            ),
            'hideModel' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/hideModel',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Process',
                        'action' => 'hideModel',
                    ),
                ),
            ),
            'save-styles' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/save-style',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Process',
                        'action' => 'saveFontSyle',
                    ),
                ),
            ),
            'autocomplete-users' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/autocomplete',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Process',
                        'action' => 'autoComplete',
                    ),
                ),
            ),
            'calendar' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/calendar[/:timezone]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'calendar',
                    ),
                ),
            ),
            'pledges' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pledges',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Pledge',
                        'action' => 'index',
                    ),
                ),
            ),
            'filters' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/filters',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'filter',
                    ),
                ),
            ),
            'session-rating' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/process/rate-session',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Process',
                        'action' => 'rateSession',
                    ),
                ),
            ),
            'event-calendar' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/process/event-calendar',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Process',
                        'action' => 'eventCalendar'
                    )
                ),
            ),
            'performer' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/performer',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'profile' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:'.Injector::USER.'/:name/:action[/:filter][/:page][/:type[/:token]]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Performer',
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
            'process-ajax' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/processAjax[/:getdata]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Process',
                        'action' => 'index',
                        'getdata' => null,
                    ),
                ),
            ),
            'lobby' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/lobby',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Lobby',
                        'action' => 'index',
                    ),
                ),
            ),
            'play' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/play[/:action[/:'.Injector::USER.']]',
                    'defaults' => array(
                        'controller' => \Application\Controller\PlayController::class,
                        'action' => 'index',
                    ),
                ),
            ),
            'messages' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/messages/:'.Injector::USER.'[/:type]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Messages',
                        'action' => 'messages',
                        'type' => 'inbox'
                    ),
                    'constraints' => array(
                      'type' => 'inbox|archive|sent'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'compose' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/compose',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Messages',
                                'action' => 'compose'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'send-to' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:'.Injector::TOUSER,
                                    'defaults' => array(
                                        'controller' => 'Application\Controller\Messages',
                                        'action' => 'compose'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'sent' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/sent',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Messages',
                                'action' => 'sent-messages'
                            ),
                        ),
                    ),
                    'view' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/view/:'.Injector::MESSAGE.'/:slug',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Messages',
                                'action' => 'view'
                            ),
                        ),
                    ),
                ),
            ),
/*            'crud-update-comments' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/crud-comments/update-row',
                        'defaults' => array(
                            'controller' => 'Crud\\Controller\\RbComments',
                            'action' => 'updateRow'
                        ),
                    ),
            )*/
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'scheldule-alert' => [
                    'options' => array(
                        'route' => 'cron:schedule-alert',
                        'defaults' => array(
                            'controller' => \Application\Controller\CronController::class,
                            'action' => 'scheduleAlert'
                        )
                    )
                ]
            )
        )
    ),
);
