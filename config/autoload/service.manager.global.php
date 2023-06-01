<?php

return array(

    'service_manager' => array(

        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),

        'invokables' => array(
            'Application\Listener\MessageListener' => 'Application\Listener\MessageListener',
        ),

        'factories' => array(
            'BjyAuthorize\Service\Authorize' => 'Application\Service\AuthorizeFactory',
        ),

    ),

);
