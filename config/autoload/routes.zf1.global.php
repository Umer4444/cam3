<?php
use Application\Entity\Role;

return array(
    'router' => array(
        'routes' => array(
            'process' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/process',
                    'defaults' => array(
                        'controller' => 'zf1',
                        'action' => 'process',
                    ),
                ),
                'may_terminate' => true,
            ),
            'performer-special-requests' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/performer/:id_model/:name/special-requests',
                    'defaults' => array(
                        'controller' => 'zf1',
                    ),
                ),
                'may_terminate' => true,
            ),
            'static-pages' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/page/:page',
                    'defaults' => array(
                        'controller' => 'zf1',
                        'page' => 'faq'
                    ),
                ),
            ),
            'development-pages' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pages/:page',
                    'defaults' => array(
                        'controller' => 'zf1',
                        'page' => 'start'
                    ),
                ),
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'broadcast' => array(
                        'child_routes' => array(
                            'settings' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/settings',
                                ),
                            ),
                        )
                    ),
                    'profile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/profile',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'settings' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/settings',
                                ),
                            ),
                        )
                    ),
                    'account' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/account',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'settings' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/settings',
                                ),
                            ),
                        )
                    ),
                    'notifications' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/notifications',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'settings' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/settings',
                                ),
                            ),
                        )
                    ),
                    'payment' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/payment',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'info' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/info',
                                ),
                            ),
                        )
                    ),
                    'privacy' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/privacy',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'settings' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/settings[/:type[/page/:page]]',
                                )
                            ),
                        )
                    ),
                    'train' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/train',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'auto-responders' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/auto-responders',
                                ),
                            ),
                        )
                    ),
                    'chat' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/chat',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'quotes' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/quotes',
                                ),
                            ),
                            'sounds' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/sounds',
                                ),
                            ),
                        )
                    ),
                    'schedule' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/schedule',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'events' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/events',
                                ),
                            ),
                        )
                    ),
                    'rules' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/rules[/:for]',
                            'defaults' => array(
                                'controller' => 'zf1',
                                'for' => Role::USER
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'crud' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:id/:manage',
                                ),
                            ),
                        )
                    ),
                    'bad-words-filter' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/bad-words-filter',
                            'defaults' => array(
                                'controller' => 'zf1'
                            ),
                        ),
                    ),
                    'system' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/system',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'logs' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/logs',
                                ),
                            ),
                        )
                    ),
                    'revenue' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/revenue',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'stats' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/stats',
                                ),
                            ),
                        )
                    ),
                    'my-notes' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/my-notes[/:action_type/:id_note]',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'user-notes' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/user-notes[/page/:page]',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'upload' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/upload[/:type]',
                            'defaults' => array(
                                'controller' => 'zf1'
                            ),
                        )
                    ),
                    'manage' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/manage',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                        'child_routes' => array(
                            'photos' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/photos[/:type]',
                                ),
                            ),
                            'videos' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/videos[/:type]',
                                ),
                            ),
                            'blogs' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/blogs',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'page' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/page/:page',
                                        ),
                                    )
                                )
                            ),
                            'pledge' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/pledge/view[/:id_pledge/:title]',
                                    'defaults' => array(
                                        'controller' => 'PledgeController',
                                    ),
                                ),
                            ),
                            'pledges' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/pledges',
                                ),
                            ),
                            'banners' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/banners/:type[/:id]',
                                    'defaults' => array(
                                        'type' => 'list'
                                    )
                                ),
                            ),
                            'pages' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/pages',
                                    'defaults' => array(
                                        'controller' => 'zf1',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'page' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:page',
                                        )
                                    ),
                                )
                            ),
                            'development' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/development',
                                    'defaults' => array(
                                        'controller' => 'zf1',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'action' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '[/manage]/:type',
                                        ),
                                    ),
                                )
                            ),
                        )
                    ),
                    'announcements' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/announcements[/:type]',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),




















                    'blog-edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/post/edit/:id',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-order' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/model-order',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),

                    'templates' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/templates',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'system-settings' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/system-settings',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'watermarks' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/watermarks',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'bad-words' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/bad-words',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'schedule-event' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/schedule-events',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'performer-account-settings' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/account-settings',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'privacy-settings' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/privacy-settings[/:extra]',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-train-auto-responders' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/train-auto-responders',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-quotes' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/quotes',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-bad-word' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/bad-word-filter',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-manage-photos' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/manage-photos/:type',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Photos',
                                //'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-manage-videos' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/manage-videos',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-blog-list' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/blog/list',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-blog-list-page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/list/page/:page',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-blog-post-page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/post/view/:id_post/:title',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-blog-post-add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/blog/post/add',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-categories-list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/categories/list',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-categories-list-page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/categories/list/page/:page',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-categories-edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/categories/edit/:id/:title',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-categories-add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/blog/categories/add',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/add',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/edit/:id_pledge/:title',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-delete' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/delete/:id_pledge/:title',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/update/:id_pledge/add',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-perk-add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/perks/:id_pledge/add',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-perk-edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/perks/:id_pledge/edit/:id_perk/:title',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-photos' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/photos/:id_pledge/:title',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/list',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-pledge-list-page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/pledge/list/page/:page',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-banners-add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/banners/add',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-banners-edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/banners/edit/:id_banner',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-banners-delete' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/banners/delete/:id_banner',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-banners-list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/banners/list',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-banners-list-page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/banners/list/page/:page',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-messages' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/my-messages/:message_action',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                    'model-messages-page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/my-messages/:message_action/page/:page',
                            'defaults' => array(
                                'controller' => 'zf1',
                            ),
                        ),
                    ),
                )
            ),
            'live-broadcast' => array(
                'type' => 'Segment',
                'priority' => 1000,
                'options' => array(
                    'route' => '/live-broadcast/:id_model',
                    'defaults' => array(
                        'controller' => 'zf1',
                        'action' => 'zf2bare'
                    ),
                ),
            ),
        ),
    ),
);