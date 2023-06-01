<?php
return array(
    'bjyauthorize' => array(
        'guards' => array(
            \BjyAuthorize\Guard\Controller::class => array(
                array(
                    'controller' => [
                        'Pages\Controller\Index',
                        'Solo\Controller\Index',
                    ]
                ),
            ),
        ),
    ),
);
