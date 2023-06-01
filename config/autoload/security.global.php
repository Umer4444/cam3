<?php
use Application\Entity\Role;

return array(
    'bjyauthorize' => array(

        // set the 'guest' role as default (must be defined in a role provider)
        //'default_role' => 'guest',

        /* this module uses a meta-role that inherits from any roles that should
        * be applied to the active user. the identity provider tells us which
        * roles the "identity role" should inherit from.
        *
        * for ZfcUser, this will be your default identity provider
        */
        //'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',
        //  use DoctrineEntity identity provider
        //  'identity_provider'     => 'BjyAuthorize\Provider\Identity\ZfcUserDoctrineEntity',
        // If you only have a default role and an authenticated role, you can
        // use the 'AuthenticationIdentityProvider' to allow/restrict access
        // with the guards based on the state 'logged in' and 'not logged in'.

        'default_role' => 'guest', // not authenticated
        'authenticated_role' => 'user', // authenticated
        'identity_provider' => \BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider::class,

        'unauthorized_strategy' => \BjyAuthorize\View\RedirectionStrategy::class,
        //'unauthorized_strategy' => \BjyAuthorize\View\UnauthorizedStrategy::class,

        /* role providers simply provide a list of roles that should be inserted
        * into the Zend\Acl instance. the module comes with two providers, one
        * to specify roles in a config file and one to load roles using a
        * Zend\Db adapter.
        */
        'role_providers' => array(

            /* here, 'guest' and 'user are defined as top-level roles, with
            * 'admin' inheriting from user
            */

            \BjyAuthorize\Provider\Role\Config::class => array(
                'guest' => array(),
                'user' => array(),
                'admin' => array(),
                'super_admin' => array(),
                'account_manager' => array(),
                'studio_manager' => array(),
                'studio' => array(),
                'moderator' => array(),
                'performer' => array(),
                'vip_user' => array(),
            ),

            // this will load roles from
            // the 'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' service
            \BjyAuthorize\Provider\Role\ObjectRepositoryProvider::class => array(
                // class name of the entity representing the role
                'role_entity_class' => 'Application\Entity\Role',
                // service name of the object manager
                'object_manager' => 'Doctrine\ORM\EntityManager',
            ),
        ),

        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            \BjyAuthorize\Provider\Resource\Config::class => array(
                'broadcast' => [
                    'go-live' => [],
                    'chat-settings' => [],
                    'chat-notifiers' => [],
                    'chat-sounds' => [],
                ],
                'my-account' => [
                    'profile-settings' => [],
                    'account-settings' => [],
                    'payment-info' => [],
                    'privacy-settings' => [],
                    'train-auto-responders' => [],
                    'chat-quotes' => [],
                    'schedule-events' => [],
                    'bad-words-filter' => [],
                    'friends' => [],
                ],
                'development' => [],
                'statistics' => [
                    'revenue' => []
                ],
                'system-logs' => [],
                'upload' => [],
                'photos' => [
                    'manage-photos' => [],
                ],
                'videos' => [
                    'manage-videos' => [],
                ],
                'media' => [],
                'blog' => [
                    'manage-blogs' => [],
                ],
                'pledge' => [
                    'manage-pledges' => [],
                ],
                'albums' => [
                    'manage-albums' => [],
                ],
                'social' => [],
                'snapshot' => [],
                'banners' => [],
                'play' => [],
                'session' => [],
                'price' => [],
                'payout' => [
                    'default-payout' => [],
                ],
                'notifications' => [],
                'notifications-settings' => [],
                'security-logs' => [],
                'login' => [],
                'notes' => [
                    'my-notes' => [],
                    'user-notes' => [],
                ],
                'loggedin' => [],
                'admin' => [],
                'buy-credit' => [],
                'store' => [],
                'logo' => [],
                'chat-background' => [],
                'static-pages' => [],
                'rules' => [],
                'record' => [],
                'manage-profile-page' => [],
                'settings' => [],
                'announcements' => array(),


                'categories' => array(),
                'sites' => array(),
                'user-profile' => array(),
                'order' => array(),
                'message' => array(),
                'static' => array(),
                'email' => array(),
                'system' => array(),
                'watermarks' => array(),

                'badwords' => array(),
                'models-active' => array(),
                'models-pending' => array(),
                'models-denied' => array(),
                'models-add' => array(),
                'reviews' => array(),
                'pledges' => array(),
                'users-active' => array(),
                'users-pending' => array(),
                'users-denied' => array(),
                'moderators-active' => array(),
                'moderators-pending' => array(),
                'moderators-denied' => array(),
                'moderators-add' => array(),
                'moderatorsParent' => array(),
                'usersParent' => array(),
                'modelsParent' => array(),
                'settingsParent' => array(),
                'comments' => array(),
                'accountSettings' => array(),
                'privacySettings' => array(),
                'trainAutoResponders' => array(),
                'quotes' => array(),
                'messages' => array(),
                'scheduleEvents' => array(),
                'model-statistics' => array(),
                'mediaParent' => array(),
                'uploadMedia' => array(),
                'manageBlog' => array(),
                'managePledges' => array(),
                'manageBanners' => array(),
                'equipment' => array(),
                'profilePage' => array(),
                'freeChat' => array(),
                'fishing' => array(),
                'actingPerforming' => array(),
                'equipWebcam' => array(),
                'equipInternet' => array(),
                'surroundings' => array(),
                'clothing' => array(),
                'private' => array(),
                'group' => array(),
                'attitude' => array(),
                'safety' => array(),
                'gettingStarted' => array(),
                'roomStudio' => array(),
                'performance' => array(),
                'marketing' => array(),
                'video' => array(),
                'fantasy' => array(),
                'set-video' => array(),
                'newsletter-list' => array(),
                'subscribers' => array(),
                'logout' => array(),
                'root' => array(),
                'all-users' => array(),
                'moderateFriends' => array(),
                'presentations' => array(),
                'about' => array(),
                'home' => array(),
            ),
        ),

        /* rules can be specified here with the format:
        * array(roles (array), resource, [privilege (array|string), assertion])
        * assertions will be loaded using the service manager and must implement
        * Zend\Acl\Assertion\AssertionInterface.
        * *if you use assertions, define them using the service manager!*
        */
        'rule_providers' => array(
            \BjyAuthorize\Provider\Rule\Config::class => array(
                'allow' => array(

                    [
                        ['performer'],
                        [
                            'broadcast', 'go-live', 'chat-settings', 'profile-settings', 'account-settings',
                            'payment-info', 'privacy-settings', 'train-auto-responders', 'chat-quotes', 'record',
                            'schedule-events', 'bad-words-filter', 'friends', 'statistics', 'price', 'chat-notifiers',
                            'revenue', 'notes', 'media', 'photos', 'videos', 'upload',
                            'blog', 'pledge', 'notifications', 'social', 'session', 'play', 'banners', 'loggedin',
                            'admin', 'store', 'moderateFriends', 'newsletter-list', 'subscribers', 'manage-profile-page',
                            'pledges', 'presentations', 'manage-albums', 'user-profile', 'about', 'home', 'chat-sounds',
                            'announcements'
                        ],
                        ['view']
                    ],

                    [
                        ['user'],
                        [
                            'loggedin', 'buy-credit', 'settings'
                        ],
                        ['view']
                    ],

                    [
                        ['admin', 'super_admin'],
                        [
                            'loggedin', 'admin', 'chat-background', 'logo', 'store', 'security-logs', 'system-logs',
                            'photos', 'videos', 'upload', 'media', 'static-pages', 'rules', 'development',
                            'statistics', "user-profile", 'manage-albums', 'default-payout', 'manage-blogs',
                            'schedule-events', 'announcements'
                        ],
                        ['view', 'edit', 'admin']
                    ],

                    [
                        ['admin', 'super_admin'],
                        [
                            'broadcast'
                        ],
                        ['admin']
                    ],

                    [
                        ['guest'],
                        [
                            'login'
                        ],
                        ['view']
                    ],

                ),
                'deny' => array(),
            ),
        ),

        'guards' => array(

            \BjyAuthorize\Guard\Controller::class => array(

                 array(
                    'controller' => 'Application\Controller\Photos',
                    'roles' => array(
                        Role::PERFORMER
                    )
                 ),
                 array(
                    'controller' => 'Application\Controller\Lobby',
                    'roles' => Role::getLoggedInRoles()
                 ),
                array(
                    'controller' => 'UserProfile\Controller\User',
                    'roles' => Role::getLoggedInRoles()
                ),
                array(
                   'controller' => \Application\Controller\ConfigController::class,
                   'action' => array(
                       'connect',
                       'session',
                       'payout-type',
                       'play',
                       'price',
                       'chat_notifiers',
                       'broadcast',
                   ),
                   'roles' => array(
                       Role::PERFORMER,
                   )
                ),
                array(
                   'controller' => \Application\Controller\ConfigController::class,
                   'action' => ['multiple'],
                   'roles' => Role::getLoggedInRoles()
                ),
                array(
                   'controller' => \Application\Controller\ConfigController::class,
                   'action' => array(
                       'default-payout'
                   ),
                   'roles' => array(
                       Role::ADMIN, Role::SUPER_ADMIN,
                   )
                ),
                array(
                   'controller' => \Application\Controller\ConfigController::class,
                   'roles' => array(
                       Role::USER,
                   )
                ),
                array(
                   'controller' => \Application\Controller\PlayController::class,
                    'roles' => array_merge(Role::getLoggedInRoles(), [Role::GUEST])
                ),
                array(
                    'controller' => \BjyAuthorize\Guard\Controller::class,
                ),
                 array(
                    'controller' => \Application\Controller\StreamController::class,
                    'action' => array(
                        'live',
                    ),
                 ),
                 array(
                    'controller' => \Application\Controller\StreamController::class,
                    'action' => array(
                        'broadcast', 'config'
                    ),
                    'roles' => array(
                        Role::PERFORMER, Role::USER,
                    )
                 ),

                 array(
                    'controller' => \Application\Controller\Admin\PerformerAdminController::class,
                    'action' => [
                        'manageProfilePage', 'record', 'broadcast'
                    ],
                    'roles' => array(
                        Role::PERFORMER
                    )
                 ),
                array(
                    'controller' => 'Application\Controller\Index',
                    'roles' => array(
                        Role::PERFORMER, Role::SUPER_ADMIN, Role::GUEST
                    )
                ),
                 array(
                    'controller' => \Application\Controller\NewsController::class,
                ),

                array(
                    'controller' => array(
                        'Application\Controller\Blog',
                        'UserProfile\Controller\User',
                        'Application\Controller\Pledge',
                        'Application\Controller\NotificationsController',
                        'OrgHeiglContact\Controller\ContactController',
                        'PayumCapture',
                        'RbComment\Controller\Console',
                        'Application\Controller\CustomCommentsController',
                        'RbComment\Controller\Comment',
                        'imageuploader',
                        'admin',
                        'payment',
                        'user-information',
                        'Application\Controller\UserAddressController',
                        'speckaddress',
                        'BjyAuthorize\Guard\Controller',
                    ),
                    'roles' => array(
                        'guest',
                        'user',
                        'performer'
                    )
                ),
                array(
                    'controller' => [
                        'zfcuser'

                    ],
                    'roles' => array(
                        'guest',
                        'user',
                        'performer',
                        'admin',
                        'super_admin'
                    )
                ),
                array(
                    'controller' => [
                        'Application\Controller\IndexController'

                    ],
                    'roles' => array(
                        'guest',
                        'user',
                        'performer',
                        'admin',
                        'super_admin'
                    )
                ),
                array(
                    'controller' => 'DoctrineModule\Controller\Cli',
                    'action' => 'cli',
                    'roles' => array(
                        'guest',
                    )
                ),
                array(
                    'controller' => [
                        'BitWeb\CronModule\Controller\Index',
                        \Application\Controller\CronController::class
                    ],
                    'roles' => array(
                        'guest',
                    )
                ),
                array(
                    'controller' => 'ZfcAdmin\Controller\AdminController',
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'performer' //@todo check if performers are allowed!!!!!!!
                    )
                ),
                array(
                    'controller' => array(
                        'Application\Controller\Friends',
                        'Application\Controller\Messages'
                    ),
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'performer',
                        'user' //@todo check if users are allowed!!!!!!!
                    )
                ),

                array(
                    'controller' => array(
                        'search',
                        'multisite_admin',
                        'administration',
                        'reviews',
                        'notes',
                        'notifications',
                    ),
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'performer'
                    )
                ),
                array(
                    'controller' => array(
                       'comments',
                        'videos'
                    ),
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'performer'
                    )
                ),

                array(
                    'controller' => 'Application\Controller\Process',
                    'action' => array(
                        'index',
                        'hideModel',
                        'autoComplete'
                    ),

                ),
                array(
                    'controller' => 'Application\Controller\Process',
                    'action' => array(
                        'index',
                        'rateSession',
                        'eventCalendar',
                    ),

                ),
                array(
                    'controller' => 'photos',
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'performer'
                    )

                ),
                array(
                    'controller' => 'zfcuserimpersonate_adminController',
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'moderator'
                    )

                ),
                array(
                    'controller' => 'zfcuserimpersonate_adminController',
                    'action' => array(
                        'unimpersonateUser'
                    ),
                    'roles' => array(
                        'super_admin',
                        'admin',
                        'moderator',
                        'performer',
                        'user'
                    )
                ),
                array(
                    'controller' => 'CgmConfigAdmin_ConfigOptionsController',
                ),

                array(
                    'controller' => 'Application\Controller\CoreSettings',
                    'roles' => array(
                        'admin',
                    )
                ),
                array(
                    'controller' => ['user-account', 'user-profile'],
                    'roles' => array(
                        'admin', 'user', 'performer'
                    )
                ),
                array(
                    'controller' => array(
                        'sud-zfcuser-overrides',
                        'imageuploader',
                        'admin',
                        'payment',
                        'user-information',
                        'Application\Controller\UserAddressController',
                        'Application\Controller\Photos',
                    ),
                    'roles' => array(
                        'performer',
                        'admin',
                        'super_admin'
                    )
                ),

                [
                    'controller' => 'Application\Controller\PaymentGateway',
                    'roles' => Role::getLoggedInRoles()
                ],

                [
                    'controller' => 'ZfSnapGeoip\Controller\Console',
                    'roles' => ['guest']
                ],

                [
                    'controller' => [
                        'zf1', // zf1 fallback controller
                        'Application\Controller\Contact',
                        'Application\Controller\Index',
                        'Eye4web\ZfcUser\Warnings\Controller\WarningsController',
                        Application\Controller\StoreController::class,
                        'Application\Controller\Performer',
                        'AsseticBundle\Controller\Console',
                        'ZFTool\Controller\Module',
                        'ZFTool\Controller\Config',
                        'ZFTool\Controller\Diagnostics',
                        'ZF\DevelopmentMode\DevelopmentModeController',
                    ]
                ],

            ),

        ),
    ),
);