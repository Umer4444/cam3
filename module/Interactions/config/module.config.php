<?php
return array(
    'view_helpers' => array(
        'invokables' => array(
            'likes' => 'Interactions\View\Helper\Likes',
            'rating' => 'Interactions\View\Helper\Rating',
            'interaction' => 'Interactions\View\Helper\Interaction',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'likes' => __DIR__ . '/../view/partials/likes.twig',
            'rating' => __DIR__ . '/../view/partials/rating.twig',
        )
    ),
    'assetic_configuration' => array(
        'routes' => [
            '(.*)' => [
                '@head_interaction_js',
                '@head_interaction_css',
            ],
        ],
        'modules' => array(
            'Interactions' => array(
                'root_path' => __DIR__ . '/../assets',
                'collections' => array(
                    'head_interaction_js' => array(
                        'assets' => array(
                            'js/interactions.js',
                            //'../../../vendor/javascript/jRating/jquery/jRating.jquery.js',
                        ),
                        'filters' => array(
                            '?JSMinFilter' => array(
                                'name' => 'Assetic\Filter\JSMinFilter'
                            ),
                        ),
                    ),
                    'head_interaction_css' => array(
                        'assets' => array(
                            //'../../../vendor/javascript/jRating/jquery/jRating.jquery.css',
                            'css/interactions.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),
                    'static' => array(
                        'assets' => array(
                            '../../../vendor/javascript/jRating/jquery/icons/*',
                            'css/images/icons/*',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'InteractionEntityDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../src/Interactions/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Interactions\Entity' => 'InteractionEntityDriver',
                ),
            ),
        ),
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Interactions\Listener\InteractionListener',
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'api.rest.doctrine.interactions' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/interaction[/:id]',
                    'defaults' => array(
                        'controller' => 'Interactions\\API\\V1\\Rest\\Interactions\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            'api.rest.doctrine.interactions',
        ),
    ),
    'zf-rest' => array(
        'Interactions\\API\\V1\\Rest\\Interactions\\Controller' => array(
            'listener' => 'Interactions\\API\\V1\\Rest\\Interactions\\InteractionResource',
            'route_name' => 'api.rest.doctrine.interactions',
            'route_identifier_name' => 'id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'interactions',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
            ),
            'collection_http_methods' => array(
                0 => 'PATCH',
                1 => 'GET',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Interactions\\Entity\\Interaction',
            'collection_class' => 'Interactions\\API\\V1\\Rest\\Interactions\\InteractionCollection',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Interactions\\API\\V1\\Rest\\Interactions\\Controller' => 'HalJson',
        ),
        'accept-whitelist' => array(
            'Interactions\\API\\V1\\Rest\\Interactions\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content-type-whitelist' => array(
            'Interactions\\API\\V1\\Rest\\Interactions\\Controller' => array(
                0 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Interactions\\Entity\\Interaction' => array(
                'route_identifier_name' => 'id',
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.doctrine.interactions',
                'hydrator' => 'Interactions\\API\\V1\\Rest\\Interactions\\InteractionHydrator',
            ),
            'Interactions\\API\\V1\\Rest\\Interactions\\InteractionCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.doctrine.interactions',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'doctrine-connected' => array(
            'Interactions\\API\\V1\\Rest\\Interactions\\InteractionResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Interactions\\API\\V1\\Rest\\Interactions\\InteractionHydrator',
            ),
        ),
    ),
    'doctrine-hydrator' => array(
        'Interactions\\API\\V1\\Rest\\Interactions\\InteractionHydrator' => array(
            'entity_class' => 'Interaction\\Entity\\Interactions',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
    ),
    'zf-content-validation' => array(
        'Interactions\\API\\V1\\Rest\\Interactions\\Controller' => array(
            'input_filter' => 'Interactions\\API\\V1\\Rest\\Interactions\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Interactions\\API\\V1\\Rest\\Interactions\\Validator' => array(
            0 => array(
                'name' => 'views',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'rating',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
            2 => array(
                'name' => 'votes',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
            3 => array(
                'name' => 'likes',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
            4 => array(
                'name' => 'dislikes',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
            5 => array(
                'name' => 'entityReference',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
            6 => array(
                'name' => 'entity',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\\Guard\\Controller' => array(
                array(
                    'controller' => array(
                        'Interactions\\API\\V1\\Rest\\Interactions\\Controller',
                    ),
                    'roles' => array(
                        'user',
                        'performer',
                    ),
                ),
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Interactions\\API\\V1\\Rest\\Interactions\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
        ),
    ),
);
