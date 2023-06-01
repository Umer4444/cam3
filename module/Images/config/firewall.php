<?php
return array(
    'bjyauthorize' => array(
        'guards' => array(
            \BjyAuthorize\Guard\Controller::class => array(
                array(
                    'controller' => 'Images\Controller\Albums',
                    'roles' => array(
                        'guest',
                        'user',
                    )
                ),
                array(
                    'controller' => 'Images\Controller\Index',
                    'roles' => array(
                        'guest', 'user', 'performer',
                        'admin'
                    )
                ),
            ),
        ),
    ),
);
