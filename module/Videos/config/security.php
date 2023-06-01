<?php
return array(
    'bjyauthorize' => array(
        'guards' => array(
            \BjyAuthorize\Guard\Controller::class => array(
                array(
                    'controller' => 'Videos\Controller\Videos',
                ),
            ),
        ),
    ),
);
