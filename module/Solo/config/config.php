<?php

return array(

    'zfctwig' => array(
        'loader_chain' => array(
            'SoloFilesystemLoader'
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Solo\Controller\Index' => 'Solo\Controller\IndexController',
            'solo-address-overrides' => 'Solo\Controller\UserAddressController',
            'OrgHeiglContact\Controller\ContactController' => 'Solo\Controller\ContactController',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'modelEvents' => 'Solo\View\Helper\ModelEventsHelper',
            'modelLatestVideo' => 'Solo\View\Helper\ModelLatestVideoHelper',
            'socialMedia' => 'Solo\View\Helper\SocialMediaHelper',
            'priceHelper' => 'Solo\View\Helper\PriceHelper',
            'connectHelper' => 'Solo\View\Helper\ConnectHelper',
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'SoloFilesystemLoader' => function () {
                return new \Twig_Loader_Filesystem(__DIR__ . '/../view/layout/');
            },
        )
    ),

);
