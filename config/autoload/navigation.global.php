<?php

return array(

    'navigation' => array(

        'register' => array(
            'login' => array(
                'label' => 'Login',
                'route' => 'zfcuser/login',
                'resource' => 'login',
                'privilege' => 'view',
            ),
            'myaccount' => array(
                'label' => 'My Account',
                'uri' => '#',
                'resource' => "loggedin",
                'privilege' => "view",
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => "Account Info",
                        'route' => "user-account",
                    ),
                    array(
                        'label' => "Settings",
                        'route' => "zfcuser/config",
                        'resource' => 'settings',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => "Dashboard & Admin",
                        'route' => "zfcadmin",
                        'resource' => "admin",
                        'privilege' => "view",
                    ),
                    array(
                        'label' => "Buy Credit !",
                        'route' => "payment",
                        'resource' => "buy-credit",
                        'privilege' => "view",
                    ),
                    array(
                        'label' => 'Logout',
                        'route' => 'zfcuser/logout',
                        'resource' => 'loggedin',
                        'privilege' => 'view',
                    ),
                ),
            ),
            'register' => array(
                'label' => "Register",
                'route' => "zfcuser/register",
                'class' => "reg_link",
                'params' => array(
                    'type' => 'user'
                ),
                'resource' => "login",
                'privilege' => "view",
            ),
        ),

        'backend' => array(

            'broadcast' => array(
                'label' => 'Broadcast',
                'uri' => '#',
                'resource' => 'broadcast',
                'privilege' => 'view',
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => 'Go live',
                        'route' => 'zfcadmin/broadcast',
                        'resource' => 'go-live',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Record',
                        'route' => 'zfcadmin/record',
                        'resource' => 'record',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Chat Settings',
                        'route' => 'zfcadmin/config',
                        'action' => 'broadcast',
                        'resource' => 'chat-settings',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Chat Notifiers',
                        'route' => 'zfcadmin/config',
                        'action' => 'chat_notifiers',
                        'resource' => 'chat-notifiers',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Chat Sounds',
                        'route' => 'zfcadmin/chat/sounds',
                        'resource' => 'chat-sounds',
                        'privilege' => 'view',
                    ),
                ),
            ),

            'settings' => array(
                'label' => 'Settings',
                'uri' => '#',
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => 'Default Payout',
                        'route' => 'zfcadmin/config',
                        'resource' => 'default-payout',
                        'action' => 'default-payout',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Announcements',
                        'route' => 'zfcadmin/announcements',
                        'resource' => 'announcements',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Instant Notifications',
                        'route' => 'zfcadmin/notifications/type',
                        'action' => 'instant',
                        'resource' => 'notifications',
                        'privilege' => 'edit'
                    ),
                    array(
                        'label' => 'Logo Themes',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'logo',
                        'action' => 'list',
                        'resource' => 'logo',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Development Pages',
                        'route' => 'zfcadmin/manage/development',
                        'resource' => 'development',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Rules',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'rules',
                        'resource' => 'rules',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Chat Background Themes',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'chat-background',
                        'action' => 'list',
                        'resource' => 'chat-background',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Static Pages',
                        'route' => 'zfcadmin/manage/pages',
                        'resource' => 'static-pages',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Model Order',
                        'uri' => '/admin/model-order',
                        'resource' => 'order',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Message Box',
                        'uri' => '/admin/messages/inbox',
                        'resource' => 'message',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Static Pages',
                        'uri' => '/admin/pages',
                        'resource' => 'static',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Email Templates',
                        'uri' => '/admin/templates',
                        'resource' => 'email',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'System Settings',
                        'uri' => '/admin/system-settings',
                        'resource' => 'system',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Watermarks',
                        'uri' => '/admin/watermarks',
                        'resource' => 'watermarks',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Upload default videos',
                        'route' => 'zfcadmin/upload-video',
                        'resource' => 'upload-video',
                        'privilege' => 'edit',
                    ),
                    array(
                        'label' => 'Subscribed users',
                        'uri' => '/admin/subscribers',
                        'resource' => 'subscribers',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Bad words Filter',
                        'uri' => '/admin/bad-words',
                        'resource' => 'badwords',
                        'privilege' => 'view'
                    ),
                ),

            ),

            'users' => array(
                'label' => 'Users',
                'uri' => '#',
                'resource' => 'users',
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => 'List',
                        'route' => 'zfcadmin/crud/default',
                        'params' => [
                            'controller' => 'user',
                            'action' => 'list',
                            'type' => 'model'
                        ],
                        'resource' => 'models-active',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Model Statistics',
                        'route' => 'zfcadmin/model-statistics',
                        'resource' => 'model-statistics',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Development Pages',
                        'uri' => '/admin/development/pages',
                        'resource' => 'development',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Reviews',
                        'uri' => '/admin/model/reviews',
                        'resource' => 'reviews',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Comments',
                        'uri' => '/admin/model/comments',
                        'resource' => 'comments',
                        'privilege' => 'view',
                    ),
                ),
            ),

            'account' => array(
                'label' => 'My Account',
                'uri' => '#',
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => 'Profile Page',
                        'route' => 'zfcadmin/manage/profile-page',
                        'resource' => 'manage-profile-page',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Edit Profile',
                        'route' => 'zfcadmin/profile/settings',
                        'resource' => 'profile-settings',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Account Settings',
                        'route' => 'zfcadmin/account/settings',
                        'resource' => 'account-settings',
                        'privilege' => 'view',
                    ),
                    /*array(
                        'label' => 'Payment Info',
                        'route' => 'zfcadmin/payment/info',
                        'resource' => 'payment-info',
                        'privilege' => 'view',
                    ),*/
                    /*array(
                        'label' => 'Privacy Settings',
                        'route' => 'zfcadmin/privacy/settings',
                        'resource' => 'privacy-settings',
                        'privilege' => 'view',
                    ),*/
                    array(
                        'label' => 'Train Auto Responders',
                        'route' => 'zfcadmin/train/auto-responders',
                        'resource' => 'train-auto-responders',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Chat quotes',
                        'route' => 'zfcadmin/chat/quotes',
                        'resource' => 'chat-quotes',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Schedule Event',
                        'route' => 'zfcadmin/schedule/events',
                        'resource' => 'schedule-events',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Bad Words Filter',
                        'route' => 'zfcadmin/bad-words-filter',
                        'resource' => 'bad-words-filter',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Friends',
                        'route' => 'zfcadmin/friends',
                        'resource' => 'friends',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Social',
                        'route' => 'zfcadmin/config',
                        'resource' => 'social',
                        'action' => 'connect',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Session',
                        'route' => 'zfcadmin/config',
                        'resource' => 'session',
                        'action' => 'session',
                        'privilege' => 'view',
                    ),
                    'price' => array(
                        'label' => 'Price',
                        'route' => 'zfcadmin/config',
                        'resource' => 'price',
                        'action' => 'price',
                        'privilege' => 'view',
                    ),
                    'payout-type' => array(
                        'label' => 'Payout Type',
                        'route' => 'zfcadmin/config',
                        'resource' => 'price',
                        'action' => 'payout-type',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Play',
                        'route' => 'zfcadmin/config',
                        'resource' => 'play',
                        'action' => 'play',
                        'privilege' => 'view',
                    ),
                    array(// @todo to be checked
                        'label' => 'Message Box',
                        'uri' => '/admin/my-messages/inbox',
                        'resource' => 'messages',
                        'privilege' => 'view',
                    ),
                ),
            ),

            'notifications' => array(
                'label' => 'Notifications',
                'uri' => '#',
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => 'Show',
                        'route' => 'zfcadmin/notifications',
                        'resource' => 'notifications',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Settings',
                        'route' => 'zfcadmin/notifications/settings',
                        'resource' => 'notifications-settings',
                        'privilege' => 'view',
                    ),
                ),
            ),

            'media' => array(
                'label' => 'Media',
                'uri' => '#',
                'resource' => 'media',
                'privilege' => 'view',
                'isDropdown' => true,
                'pages' => array(
                    array(
                        'label' => 'Manage Photos',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'photo',
                        'resource' => 'manage-photos',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Albums',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'albums',
                        'resource' => 'manage-albums',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Videos',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'video',
                        'resource' => 'manage-videos',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Blogs',
                        'route' => 'zfcadmin/manage/blogs',
                        'resource' => 'manage-blogs',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Pledges',
                        'route' => 'zfcadmin/manage/pledges',
                        'resource' => 'manage-pledges',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Manage Banners',
                        'route' => 'zfcadmin/manage/banners',
                        'resource' => 'banners',
                        'privilege' => 'view',
                    ),
                    array(
                        'label' => 'Newsletters',
                        'uri' => '/admin/crud/newsletter/list',
                        'resource' => 'newsletter-list',
                        'privilege' => 'view'
                    ),
                ),
            ),

            'notes' => [
                'label' => 'Notes',
                'resource' => 'notes',
                'privilege' => 'view',
                'uri' => '#',
                'isDropdown' => true,
                'pages' => [
                    [
                        'label' => 'My Notes',
                        'route' => 'zfcadmin/my-notes',
                        'resource' => 'my-notes',
                        'privilege' => 'view',
                    ],
                    [
                        'label' => 'User Notes',
                        'route' => 'zfcadmin/user-notes',
                        'resource' => 'user-notes',
                        'privilege' => 'view',
                    ],
                ],
            ],

            'statistics' => [
                'label' => 'Statistics',
                'uri' => '#',
                'resource' => 'statistics',
                'privilege' => 'view',
                'isDropdown' => true,
                'pages' => array(
                    [
                        'label' => 'Revenue Stats',
                        'route' => 'zfcadmin/revenue/stats',
                        'resource' => 'revenue',
                        'privilege' => 'view',
                    ],
                    [
                        'label' => 'Call Logs',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'call-log',
                        /*'resource' => 'manage-videos',
                        'privilege' => 'view',*/
                    ],
                    [
                        'label' => 'System Logs',
                        'route' => 'zfcadmin/crud/default',
                        'controller' => 'ext-log-entries',
                        'resource' => 'security-logs',
                        'privilege' => 'view',
                    ],

                ),
            ],

            'store' => [
                'label' => 'Store',
                'uri' => '/admin/store/admin/admin/dashboard/',
                'resource' => 'store',
                'privilege' => 'view',
            ]

        ),

        'frontend' => array(

            'live' => array(
                'label' => 'Live',
                'uri' => '/live',
               /* 'pages' => array(
                    array(
                        'label' => 'Go live',
                        'uri' => '/performer/broadcast',
                        'resource' => 'broadcastChild',
                        'privilege' => 'view',
                    ),
                ),*/
            ),

            'clips' => array(
                'label' => 'Clips',
                'uri' => '/clips',
            ),

            'premiers' => array(
                'label' => 'Premiers',
                'uri' => '/premiers',
            ),
            'presentations' => array(
                'label' => 'Presentations',
                'uri' => '/presentations',
               // 'resource' => 'broadcastParent',
                //'privilege' => 'view',
            ),
            'vod' => array(
                'label' => 'Vod',
                'uri' => '/vods',
               // 'resource' => 'broadcastParent',
                //'privilege' => 'view',
            ),
            'pledges' => array(
                'label' => 'Pledges',
                'uri' => '/pledges',
               // 'resource' => 'broadcastParent',
                //'privilege' => 'view',
            ),
            'recent-photos' => array(
                'label' => 'Photos',
                'route' => 'images/recent',
            ),
            'auction' => array(
                'label' => 'Blog',
                'route' => 'blog',
            ),
            'store' => array(
                'label' => 'Store',
                'route' => 'store-domain',
            ),
            /*'auction' => array(
                'label' => 'Auction',
                'route' => 'store-domain',
            ),*/
            'girls' => array(
                'label' => 'Girls',
                'uri' => '/models',
                //'route' => 'frontend/performers'
            ),
            'pledges' => array(
                'label' => 'Pledges',
                'uri' => '/pledges',
                //'route' => 'frontend/performers'
            ),
//            'calendar' => array(
//                'label' => 'Calendar',
//                'uri' => '/calendar',
//                //'route' => 'frontend/performers'
//            ),
            'play' => array(
                'label' => 'Play',
                'route' => 'play'
            ),
            'lobby' => array(
                'label' => 'Lobby',
                'route' => 'lobby'
            ),
        ),

        'performer' => array(
            'profile' => array(
                'label' => 'Home',
                'route' => 'performer/profile',
                'action' => 'profile',
            ),
            'pictures' => array(
                'label' => 'Pictures',
                'route' => 'performer/profile',
                'action' => 'pictures',
            ),
            'videos' => array(
                'label' => 'Videos',
                'route' => 'performer/profile',
                'action' => 'videos',
            ),
            'blog' => array(
                'label' => 'Blog',
                'route' => 'performer/profile',
                'action' => 'blog',
            ),
            'offers' => array(
                'label' => 'Offers',
                'route' => 'performer/profile',
                'action' => 'offers',
            ),
            'friends' => array(
                'label' => 'Friends',
                'route' => 'performer/profile',
                'action' => 'friends',
            ),
            'wall' => array(
                'label' => 'Wall',
                'route' => 'performer/profile',
                'action' => 'wall',
            ),
            'timeline' => array(
                'label' => 'Timeline',
                'route' => 'performer/profile',
                'action' => 'timeline',
            ),
        ),

        'user-profile' => array(
            'profile' => array(
                'label' => 'About Me',
                'route' => 'user/profile',
                'action' => 'profile',
            ),
            'wall' => array(
                'label' => 'Wall',
                'route' => 'user/profile',
                'action' => 'wall',
            ),
            'videos' => array(
                'label' => 'Media',
                'route' => 'user/profile',
                'action' => 'videos',
            ),
            'blog' => array(
                'label' => 'Blog',
                'route' => 'user/profile',
                'action' => 'blog',
            ),
            'timeline' => array(
                'label' => 'Timeline',
                'route' => 'user/profile',
                'action' => 'timeline',
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'backend' => \Application\Navigation\BackendFactory::class,
            'frontend' => \Application\Navigation\FrontendFactory::class,
            'register' => \Application\Navigation\RegisterFactory::class,
            'performer' => \Application\Navigation\PerformerFactory::class,
            'user-profile' => \Application\Navigation\UserFactory::class,
        ),
    ),

);