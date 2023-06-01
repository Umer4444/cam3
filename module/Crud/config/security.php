<?php

return array(

    'bjyauthorize' => array(

        'guards' => array(

            \BjyAuthorize\Guard\Controller::class => array(

                /*['controller' => 'VisioCrudModeler\Controller\Web', 'roles' => ['super_admin']],
                ['controller' => 'VisioCrudModeler\Controller\Index', 'roles' => ['super_admin']],
                ['controller' => 'VisioCrudModeler\Controller\Generator', 'roles' => ['super_admin']],*/
                ['controller' => 'Crud\Controller\BlogPosts', 'roles' => ['super_admin', 'admin', 'performer']],
                ['controller' => 'Crud\Controller\Photo', 'roles' => ['super_admin', 'admin', 'performer']],
                ['controller' => 'Crud\Controller\Video', 'roles' => ['super_admin', 'admin', 'performer']],
                ['controller' => 'Crud\Controller\User', 'roles' => ['super_admin', 'admin']],
                ['controller' => 'Crud\Controller\Logo', 'roles' => ['super_admin', 'admin']],
                ['controller' => 'Crud\Controller\Rules', 'roles' => ['super_admin', 'admin']],
                ['controller' => 'Crud\Controller\Newsletter', 'roles' => ['super_admin', 'admin', 'performer']],
                ['controller' => 'Crud\Controller\UserNewsletter', 'roles' => ['super_admin', 'admin', 'performer']],
                ['controller' => 'Crud\Controller\ChatBackground', 'roles' => ['super_admin', 'admin', 'performer']],
                [
                    'controller' => 'Crud\Controller\CallLog',
                    'action' => ['list', 'ajax-list'],
                    'roles' => ['super_admin', 'admin', 'performer']
                ],
                [
                    'controller' => 'Crud\Controller\Albums',
                    'action' => ['list', 'ajax-list', 'delete', 'update'],
                    'roles' => ['super_admin', 'admin', 'performer']
                ],
                [
                    'controller' => 'Crud\Controller\ExtLogEntries',
                    'action' => ['list', 'ajax-list'],
                    'roles' => ['admin', 'super_admin']
                ],

            ),

        ),

    ),

);
