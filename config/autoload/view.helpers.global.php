<?php
return array(
    'view_helpers' => array(
        'invokables' => array(
            'commentCustom' => 'Application\View\Helper\CommentCustom',
            'previewStats' => 'Application\View\Helper\PreviewStats',
            'user' => \Application\View\Helper\User::class,
            'newInstance' => \Application\View\Helper\NewInstance::class, // mostly for twig
            'serviceLocator' => \Application\View\Helper\ServiceLocator::class,
            'buttons' => \Application\View\Helper\Buttons::class,
            'buy' => \Application\View\Helper\Buy::class,
            'entity' => \Application\View\Helper\Entity::class,
            'chat' => \Application\View\Helper\Chat::class,
            'logo' => \Application\View\Helper\Logo::class,
            'params' => \Application\View\Helper\Params::class,
            'stream' => \Application\View\Helper\Stream::class,
            'repost' => \Application\View\Helper\Repost::class,
            'news' => \Application\View\Helper\News::class,
        ),
        'factories' => [
            'asset' => function($helpers) {
                return new \AsseticBundle\View\Helper\Asset($helpers->getServiceLocator());
            },

        ],
        'shared' => array(
            'user' => false,
        ),
    ),
);
