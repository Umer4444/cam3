<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'FriendsListener' => \API\V1\Rest\Friends\FriendsListener::class
        ),
    ),
    'router' => array(
        'routes' => array(
            'api.rest.doctrine.user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/user[/:user_id]',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rest\\User\\Controller',
                    ),
                ),
            ),
            'api.rest.doctrine.friends' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/friends',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rest\\Friends\\Controller',
                    ),
                ),
            ),
            'api.rpc.tip' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/tip',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Tip\\Controller',
                        'action' => 'tip',
                    ),
                ),
            ),
            'api.rpc.follow' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/follow/:user[/:section]',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Follow\\Controller',
                        'action' => 'follow',
                    ),
                ),
            ),
            'api.rpc.context' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/context/:type[/:user]',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Context\\Controller',
                        'action' => 'context',
                        'type' => 'menu',
                    ),
                ),
            ),
            'api.rpc.kick' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/kick/:user',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Kick\\Controller',
                        'action' => 'kick',
                    ),
                ),
            ),
            'api.rpc.resources' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/resources',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Resources\\Controller',
                        'action' => 'resources',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'api.rest.doctrine.user',
            1 => 'api.rest.doctrine.friends',
            2 => 'api.rpc.tip',
            3 => 'api.rpc.follow',
            4 => 'api.rpc.context',
            5 => 'api.rpc.kick',
            6 => 'api.rpc.resources',
        ),
    ),
    'zf-rest' => array(
        'API\\V1\\Rest\\User\\Controller' => array(
            'listener' => 'API\\V1\\Rest\\User\\Resource',
            'route_name' => 'api.rest.doctrine.user',
            'route_identifier_name' => 'user_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'user',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Application\\Entity\\User',
            'collection_class' => 'API\\V1\\Rest\\User\\UserCollection',
        ),
        'API\\V1\\Rest\\Friends\\Controller' => array(
            'listener' => 'API\\V1\\Rest\\Friends\\FriendsResource',
            'route_name' => 'api.rest.doctrine.friends',
            'route_identifier_name' => '',
            'entity_identifier_name' => 'friend',
            'collection_name' => 'friends',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'PUT',
                1 => 'POST',
                2 => 'GET',
                3 => 'PATCH',
                4 => 'DELETE',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => '25',
            'page_size_param' => null,
            'entity_class' => 'Application\\Entity\\Friends',
            'collection_class' => 'API\\V1\\Rest\\Friends\\FriendsCollection',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'API\\V1\\Rest\\User\\Controller' => 'HalJson',
            'API\\V1\\Rest\\Friends\\Controller' => 'HalJson',
            'API\\V1\\Rpc\\Tip\\Controller' => 'Json',
            'API\\V1\\Rpc\\Follow\\Controller' => 'Json',
            'API\\V1\\Rpc\\Context\\Controller' => 'Json',
            'API\\V1\\Rpc\\Kick\\Controller' => 'Json',
            'API\\V1\\Rpc\\Resources\\Controller' => 'Json',
        ),
        'accept-whitelist' => array(
            'API\\V1\\Rest\\User\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'API\\V1\\Rest\\Friends\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content-type-whitelist' => array(
            'API\\V1\\Rest\\User\\Controller' => array(
                0 => 'application/json',
            ),
            'API\\V1\\Rest\\Friends\\Controller' => array(
                0 => 'application/json',
            ),
        ),
        'accept_whitelist' => array(
            'API\\V1\\Rpc\\Tip\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\Follow\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\Context\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\Kick\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\Resources\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rest\\Friends\\Controller' => array(
                0 => 'application/json',
                1 => 'application/*+json',
                2 => 'application/x-www-form-urlencoded',
            ),
        ),
        'content_type_whitelist' => array(
            'API\\V1\\Rpc\\Tip\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\Follow\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\Context\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\Kick\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\Resources\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rest\\Friends\\Controller' => array(
                0 => 'application/json',
                1 => 'application/x-www-form-urlencoded',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Application\\Entity\\User' => array(
                'route_identifier_name' => 'user_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.doctrine.user',
                'hydrator' => 'API\\V1\\Rest\\User\\UserHydrator',
            ),
            'API\\V1\\Rest\\User\\UserCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.doctrine.user',
                'is_collection' => true,
            ),
            'Application\\Entity\\Friends' => array(
                'route_identifier_name' => '',
                'entity_identifier_name' => '',
                'route_name' => 'api.rest.doctrine.friends',
                'hydrator' => 'API\\V1\\Rest\\Friends\\FriendsHydrator',
            ),
            'API\\V1\\Rest\\Friends\\FriendsCollection' => array(
                'entity_identifier_name' => '',
                'route_name' => 'api.rest.doctrine.friends',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'doctrine-connected' => array(
            'API\\V1\\Rest\\User\\Resource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'API\\V1\\Rest\\User\\UserHydrator',
            ),
            'API\\V1\\Rest\\Friends\\FriendsResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'API\\V1\\Rest\\Friends\\FriendsHydrator',
                'listeners' => [
                    'FriendsListener'
                ]
            ),
        ),
    ),
    'doctrine-hydrator' => array(
        'API\\V1\\Rest\\User\\UserHydrator' => array(
            'entity_class' => 'Application\\Entity\\User',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
        'API\\V1\\Rest\\Friends\\FriendsHydrator' => array(
            'entity_class' => 'Application\\Entity\\Friends',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => false,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
    ),
    'zf-content-validation' => array(
        'API\\V1\\Rest\\User\\Controller' => array(
            'input_filter' => 'API\\V1\\Rest\\User\\Validator',
        ),
        'API\\V1\\Rest\\Friends\\Controller' => array(
            'input_filter' => 'API\\V1\\Rest\\Friends\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'API\\V1\\Rest\\User\\Validator' => array(
            0 => array(
                'name' => 'username',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 255,
                        ),
                    ),
                ),
            ),
            1 => array(
                'name' => 'gender',
                'required' => false,
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
            2 => array(
                'name' => 'email',
                'required' => false,
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
            3 => array(
                'name' => 'displayName',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 50,
                        ),
                    ),
                ),
            ),
            4 => array(
                'name' => 'birthday',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            5 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 128,
                        ),
                    ),
                ),
            ),
            6 => array(
                'name' => 'state',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            7 => array(
                'name' => 'avatar',
                'required' => false,
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
            8 => array(
                'name' => 'aboutMe',
                'required' => false,
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
            9 => array(
                'name' => 'joined',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            10 => array(
                'name' => 'lastLogin',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            11 => array(
                'name' => 'chips',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            12 => array(
                'name' => 'referralCode',
                'required' => false,
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
            13 => array(
                'name' => 'activationCode',
                'required' => false,
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
            14 => array(
                'name' => 'resetCode',
                'required' => false,
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
            15 => array(
                'name' => 'timezone',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            16 => array(
                'name' => 'lastActivity',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            17 => array(
                'name' => 'online',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            18 => array(
                'name' => 'session_id',
                'required' => false,
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
            19 => array(
                'name' => 'phone',
                'required' => false,
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
            20 => array(
                'name' => 'chipsPrivacy',
                'required' => false,
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
            21 => array(
                'name' => 'ipAddress',
                'required' => false,
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
        'API\\V1\\Rest\\Friends\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'position',
                'allow_empty' => false,
                'continue_if_empty' => true,
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'status',
                'continue_if_empty' => true,
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\\Guard\\Controller' => array(
                0 => array(
                    'controller' => array(
                        0 => 'API\\V1\\Rest\\User\\Controller',
                        1 => 'API\\V1\\Rest\\Friends\\Controller',
                        3 => 'API\\V1\\Rpc\\Tip\\Controller',
                        4 => 'API\\V1\\Rpc\\Follow\\Controller',
                        5 => 'API\\V1\\Rpc\\Context\\Controller',
                    ),
                    'roles' => array(
                        0 => 'super_admin',
                        1 => 'admin',
                        2 => 'user',
                        3 => 'performer',
                    ),
                ),
                1 => array(
                    'controller' => array(
                        0 => 'API\\V1\\Rpc\\Kick\\Controller',
                        1 => 'API\\V1\\Rpc\\Resources\\Controller',
                    ),
                    'roles' => array(
                        0 => 'super_admin',
                        1 => 'admin',
                        3 => 'performer',
                    ),
                ),
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'API\\V1\\Rpc\\Follow\\Controller' => array(
                'actions' => array(
                    'Follow' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'API\\V1\\Rpc\\Context\\Controller' => array(
                'actions' => array(
                    'Context' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'API\\V1\\Rpc\\Tip\\Controller' => 'API\\V1\\Rpc\\Tip\\TipControllerFactory',
            'API\\V1\\Rpc\\Follow\\Controller' => 'API\\V1\\Rpc\\Follow\\FollowControllerFactory',
            'API\\V1\\Rpc\\Context\\Controller' => 'API\\V1\\Rpc\\Context\\ContextControllerFactory',
            'API\\V1\\Rpc\\Kick\\Controller' => 'API\\V1\\Rpc\\Kick\\KickControllerFactory',
            'API\\V1\\Rpc\\Resources\\Controller' => 'API\\V1\\Rpc\\Resources\\ResourcesControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'API\\V1\\Rpc\\Tip\\Controller' => array(
            'service_name' => 'Tip',
            'http_methods' => array(
                0 => 'POST',
                1 => 'GET',
            ),
            'route_name' => 'api.rpc.tip',
        ),
        'API\\V1\\Rpc\\Follow\\Controller' => array(
            'service_name' => 'Follow',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.follow',
        ),
        'API\\V1\\Rpc\\Context\\Controller' => array(
            'service_name' => 'Context',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.context',
        ),
        'API\\V1\\Rpc\\Kick\\Controller' => array(
            'service_name' => 'Kick',
            'http_methods' => array(
                0 => 'PUT',
            ),
            'route_name' => 'api.rpc.kick',
        ),
        'API\\V1\\Rpc\\Resources\\Controller' => array(
            'service_name' => 'Resources',
            'http_methods' => array(
                0 => 'GET',
                1 => 'POST',
                2 => 'DELETE',
            ),
            'route_name' => 'api.rpc.resources',
        ),
    ),
);
