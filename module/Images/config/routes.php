<?php

use Application\Mapper\Injector;

return array(
    'router' => array(
        'routes' => array(
            'albums' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/albums[/:'.Injector::USER.']',
                    'defaults' => array(
                        'controller' => 'Images\Controller\Index',
                        'action' => 'albums'
                    ) ,
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'album' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:'.Injector::ALBUM.'/:slug',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'album'
                            )

                        ),
                    ),
                ),
            ),
            'images' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/image',
                    'defaults' => array(
                        'controller' => 'Images\Controller\Index',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'image' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:'.Injector::IMAGE.'/:slug',
                            'defaults' => array(
                                'action' => 'image'
                            )
                        ),
                    ),
                    'recent' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/recent[/:page]',
                            'defaults' => array(
                                'action' => 'recent'
                            ),
                            'constraints' => array(
                                'page' => '[0-9]*',
                            ),
                        ),
                    ),
                ),
            ),
            'cgmConfigAdminImages' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/imagesSettings',
                    'defaults' => array(
                        'controller' => 'Images\Controller\Index',
                        'action' => 'cgmConfigAdmin',
                    ),
                ),
            ),
            'media' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/account/media',
                    'defaults' => array(
                        'controller' => 'Images\Controller\Index',
                        'action' => 'media'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'addImage' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/images/add',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'addImage',
                            ),
                        ),
                    ),
                    'addAlbum' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/album/add',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'addAlbum',
                            ),
                        ),
                    ),
                    'list-images' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/images/list',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'imagesList',
                            ),
                        ),
                    ),
                    'preview-images' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/image[/:slug]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Images\Controller\UserIndex',
                                'action' => 'imagesPreview',
                            ),
                        ),
                    ),
                    'images-ajax' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/images/ajax',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'imagesAjax',
                            ),
                        ),
                    ),
                    'delete-images' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/images/delete[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'imagesDelete',
                            ),
                        ),
                    ),
                    'edit-images' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/images/edit[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'imagesEdit',
                            ),
                        ),
                    ),
                    'updateRow' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/images/updateRow',
                            'defaults' => array(
                                'controller' => 'Images\Controller\Index',
                                'action' => 'updateRow',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);