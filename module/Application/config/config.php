<?php
return array(
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'route' => 'Application\View\Helper\Route',
            'photos' => 'Application\View\Helper\PhotosHelper',
            'stars' => 'Application\View\Helper\StarsHelper',
            'ago' => 'Application\View\Helper\AgoHelper',
            'getPhotoThumb' => 'Application\View\Helper\PhotoThumbHelper',
            'countNotifications' => 'Application\View\Helper\CountNotifications',
            'countMessages' => 'Application\View\Helper\CountMessages',
            'modelFriends' => 'Application\View\Helper\ModelFriends',
            'eventCalendar' => 'Application\View\Helper\EventCalendar',
            'slugify' => 'Application\View\Helper\SlugifyHelper',
            'topPayers' => 'Application\View\Helper\TopTippersOrGifters',
            'moderate' => 'Application\View\Helper\Moderate',
            'videoCategories' => 'Application\View\Helper\VideoCategories',
            'nextshow' => 'Application\View\Helper\NextShow',
            'nextBlog' => 'Application\View\Helper\NextBlog',
            'timezone' => 'Application\View\Helper\Timezone',
            'filterNav' => Application\View\Helper\FilterNav::class,
            'badges' => 'Application\View\Helper\BadgesHelper',
            'session' => 'Application\View\Helper\Session',
            'disclaimer' => 'Application\View\Helper\Disclaimer',
            //'topPayersHelper' => 'Application\View\Helper\TopTippersOrGifters',

        ),
        'factories' => array(
            // @todo replace with https://github.com/tasmaniski/zf2-config-helper
            'GlobalVars' => function ($pluginManager) {
                $serviceLocator = $pluginManager->getServiceLocator();
                $viewHelper = new \Application\View\Helper\GlobalVars();
                $viewHelper->setServiceLocator($serviceLocator);
                return $viewHelper;
            },
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\PaymentGateway' => 'Application\Controller\PaymentGatewayController',
            'Application\Controller\Messages' => 'Application\Controller\MessagesController',
            'Application\Controller\Contact' => 'Application\Controller\ContactController',
            'Application\Controller\Blog' => 'Application\Controller\BlogController',
            'Application\Controller\Pledge' => 'Application\Controller\PledgeController',
            'Application\Controller\Friends' => 'Application\Controller\FriendsController',
            \Application\Controller\ConfigController::class => \Application\Controller\ConfigController::class,
            \Application\Controller\StreamController::class => \Application\Controller\StreamController::class,
            \Application\Controller\StoreController::class => \Application\Controller\StoreController::class,
            \Application\Controller\CronController::class => \Application\Controller\CronController::class,
            \Application\Controller\Admin\PerformerAdminController::class => \Application\Controller\Admin\PerformerAdminController::class,
            'reviews' => 'Application\Controller\ReviewsController',
            'comments' => 'Application\Controller\CommentsController',
            'photos' => 'Application\Controller\PhotosController',
            'Application\Controller\Videos' => 'Application\Controller\VideosController',
            'Application\Controller\Performer' => 'Application\Controller\PerformerController',
            'Application\Controller\Process' => 'Application\Controller\ProcessController',
            'Application\Controller\Fixtures' => 'Application\Controller\FixturesController',
            'Application\Controller\Lobby' => 'Application\Controller\LobbyController',
            \Application\Controller\PlayController::class => \Application\Controller\PlayController::class,
            \Application\Controller\NewsController::class => \Application\Controller\NewsController::class,
            'notifications' => 'Application\Controller\NotificationsController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'user' => \Application\Controller\Plugin\User::class,
            'cfg' => \Application\Controller\Plugin\Config::class,
        )
    ),
    'paginators' => array(
        'doctrine' => array(
            'Application\Paginator\BlogPaginator' => \Application\Entity\BlogPosts::class,
            'Application\Paginator\PopularPaginator' => \Application\Entity\User::class
        ),
    ),
    'data-fixture' => array(
        'Application_fixtures' => __DIR__ . '/../src/Application/Fixtures',
    ),
);
