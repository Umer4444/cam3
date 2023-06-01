<?php


return array(

    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',

        ),
        'aliases' => array(
            'zfcuser_doctrine_em' => 'Doctrine\ORM\EntityManager'
        ),
    ),

    'paginators' => array(
        'doctrine' => array(
            'Images\Paginator\ImagesPaginator' => \Images\Entity\Photo::class
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Images\Controller\Index' => 'Images\Controller\IndexController',
            'Images\Controller\Albums' => 'Images\Controller\AlbumsController',
            'Images\Controller\UserIndex' => 'Images\Controller\UserIndexController',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/editimages' => __DIR__ . '/../view/images/index/add-image.phtml',
            'partial/images' => __DIR__ . '/../view/images/partials/images.twig',
            'album/item' => __DIR__ . '/../view/partials/images/album-item.twig',
            'image/item' => __DIR__ . '/../view/partials/images/image-item.twig',
            'image/related-image-inner' => __DIR__ . '/../view/partials/images/related-image-inner.twig',
            'photo/index-item' => __DIR__ . '/../view/partials/images/photo.twig',

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    /*'cgmconfigadmin' => array(
        'config_groups' => array(
            'site' => array(
                'WebinoImageThumb' => array('label' => 'Image Options', 'sort' => -100),
                'solo.ionut.dev.perfectweb.ro' => array('label' => 'Solo Anakaliyah', 'sort' => -100),
            ),

        ),

        'config_options' => array(
            'site' => array(
                'WebinoImageThumb' => array(
                    'size' => array('input_type' => 'select', 'label' => 'Select a size for the images', 'value_options' =>
                        array('800x600', '1024x728', '1280x1024', '1600x1200', 'custom')),
                    'test' => 'no',
                    'Do you want to constrain proportions? (if you select no, the images will be cropped)' => true
                ),
                'solo.ionut.dev.perfectweb.ro' => array(

                    'model_id' => '29',

                )
            ),

        ),
    ),*/

);
