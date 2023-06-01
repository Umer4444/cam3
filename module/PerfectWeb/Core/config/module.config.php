<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'PerfectWebCoreEntityDriver' => array(
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'paths' => array(
                    __DIR__ . '/../src/Core/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'PerfectWeb\Core\Entity' => 'PerfectWebCoreEntityDriver',
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'em' => 'Doctrine\ORM\EntityManager',
            'user' => 'zfcuser_auth_service',
            'mail' => 'mailservice',
        ),
        'abstract_factories' => array(
            \PerfectWeb\Core\Factory\ConfigFactory::class
        ),
        'invokables' => array(
            \PerfectWeb\Core\Listener\EntityListenerFactory::class => \PerfectWeb\Core\Listener\EntityListenerFactory::class,
            'cgmconfigadmin' => \PerfectWeb\Core\Service\ConfigAdmin::class,
            'cgmconfigadmin_configgroupfactory' => \PerfectWeb\Core\Factory\ConfigGroupFactory::class,
        ),
        'factories' => array(
            'cgmconfigadmin_module_options' => \PerfectWeb\Core\Factory\ConfigOptionsFactory::class,
            'cgmconfigadmin_configvalues_mapper' => function ($sm) {
                return new \PerfectWeb\Core\Mapper\ConfigValuesMapper(
                    $sm->get('em'),
                    $sm->get('cgmconfigadmin_module_options')
                );
            },
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'related' => __DIR__ . '/../view/perfect-web/partials/related.twig',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'related' => \PerfectWeb\Core\View\Helper\Related::class,
            'crypt' => \PerfectWeb\Core\View\Helper\Crypt::class,
            'cfg' => \PerfectWeb\Core\View\Helper\Config::class,
            'object' => \PerfectWeb\Core\View\Helper\Object::class,
        ),
        'shared' => array(
            'cfg' => false
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            \PerfectWeb\Core\Controller\ConfigController::class => \PerfectWeb\Core\Controller\ConfigController::class,
        ),
    ),
    'zfctwig' => array(
        'extensions' => array(
            \PerfectWeb\Core\Twig\Extensions\Json::class,
        )
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'config' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/config[/:group]',
                            'defaults' => array(
                                'controller' => \PerfectWeb\Core\Controller\ConfigController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
