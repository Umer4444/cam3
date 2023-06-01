<?php

return array(

    'assetic_configuration' => array(

        'routes' => [
            'visio-crud-modeler' => ['@visio_modeler']
        ],

        'modules' => array(
            'AsseticBundle' => array(
                'collections' => array(
                    'visio_modeler' => array(
                        'assets' => array(
                            'vendor/hyphers/visio-crud-zf2/src/VisioCrudModeler/Public/modeler.js',
                        ),
                    ),
                ),
            ),
        ),

    ),

    'doctrine' => [
        'fixture' => array(
            'Application_fixture' => __DIR__ . '/../../module/Application/src/Application/Fixtures',
        )
    ],

    'bjyauthorize' => array(
        'guards' => array(
            \BjyAuthorize\Guard\Controller::class => array(
                [
                    'controller' => [
                        'ZF\Apigility\Admin\Controller\App',
                        'ZF\Apigility\Admin\Controller\Module',
                        'ZF\Apigility\Documentation\Controller',
                        'ZF\Apigility\Admin\Controller\DbAdapter',
                        'ZF\Apigility\Admin\Controller\Authorization',
                        'ZF\Apigility\Admin\Controller\Source',
                        'ZF\Apigility\Admin\Controller\Authentication',
                        'ZF\Apigility\Admin\Controller\ContentNegotiation',
                        'ZF\Apigility\Admin\Controller\RestService',
                        'ZF\Apigility\Admin\Controller\RpcService',
                        'ZF\Apigility\Admin\Controller\Dashboard',
                        'ZF\Apigility\Admin\Controller\Hydrators',
                        'ZF\Apigility\Admin\Controller\DoctrineAdapter',
                        'ZF\Apigility\Doctrine\Admin\Controller\DoctrineAutodiscovery',
                        'ZF\Apigility\Doctrine\Admin\Controller\DoctrineRestService',
                        'ZF\Apigility\Doctrine\Admin\Controller\DoctrineMetadataService',
                        'ZF\Apigility\Admin\Controller\InputFilter',
                        'ZF\Apigility\Admin\Controller\Filters',
                    ],
                    'roles' => ['super_admin']
                ],
                [
                    'controller' => [
                        'SanSessionToolbar\Controller\SessionToolbar',
                        'ZFTool\Controller\Create',
                        'DoctrineORMModule\Yuml\YumlController',
                    ]
                ]
            ),
        ),
    ),

    'zenddevelopertools' => array(
        'profiler' => array(
            'enabled' => true,
            'strict' => false,
            'flush_early' => false,
        ),
        'toolbar' => array(
            'enabled' => true,
            'version_check' => true,
        ),
    ),

);
