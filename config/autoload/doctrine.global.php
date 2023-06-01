<?php

return array(
    'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Tree\TreeListener',
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\Sortable\SortableListener',
                    'Gedmo\Loggable\LoggableListener',
                    'Gedmo\Sluggable\SluggableListener',
                    'Gedmo\Uploadable\UploadableListener',
                ),
            ),
        ),
        'driver' => array(
            'ApplicationEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../module/Application/src/Application/Entity'
                )
            ),
            'ImagesEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../module/Images/src/Images/Entity'
                )
            ),
            'VideosEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../module/Videos/src/Videos/Entity'
                )
            ),
            'SoloEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../module/Solo/src/Solo/Entity'
                )
            ),
            'LoggableEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity'
                )
            ),
            'PayumOrderEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver',
                'paths' => array(
                    __DIR__ . '/../../module/Application/src/Application/Extended/Payum/Core/Bridge/Doctrine/Resources/mapping' => 'Payum\Core\Model'
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'ApplicationEntityDriver',
                    'ZfcUser\Entity' => 'ApplicationEntityDriver',
                    'Images\Entity' => 'ImagesEntityDriver',
                    'Videos\Entity' => 'VideosEntityDriver',
                    'Solo\Entity' => 'SoloEntityDriver', //@todo move to application
                    'Gedmo\Loggable\Entity' => 'LoggableEntityDriver',
                    'Payum\Core\Model' => 'PayumOrderEntityDriver',
                ),
            ),
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default'
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'entity_listener_resolver' => \PerfectWeb\Core\Listener\EntityListenerFactory::class,
                // @todo uncomment when https://github.com/doctrine/DoctrineORMModule/pull/410 is merged
                'defaultQueryHints' => array(
                    'doctrine.customOutputWalker' => \Application\Extended\Doctrine\Walkers\Custom::class,
                ),
                'filters' => array(
                    'active' => \Application\Extended\Doctrine\Filter\ActiveFilter::class,
                    'category' => \Application\Extended\Doctrine\Filter\CategoryFilter::class,
                ),
            ),
        ),
        'migrations_configuration' => array(
            'orm_default' => array(
                'directory' => 'data/Migrations',
                'name' => 'Migrations Name',
                'namespace' => 'Application\Migrations',
                'table' => 'migrations',
                'column' => 'version',
            ),
        ),
    ),
    'service_manager' => array(
        /*'invokables' => array(
            'application.doctrine.filter.active' => 'Application\Extended\Doctrine\Filter\ActiveFilter'
        ),
        'abstract_factory' => array(
            'Application\Extended\Doctrine\Filter\AbstractFilterFactory'
        ),*/
    ),

);