<?php
return array(
    'crud_controllers' => array( //@todo this should become deprecated and replaced with visio
        // Example 1 (short)
        'Application\Controller\Friends' => array(
            'entity_class' => 'Application\Entity\Friends',
            'form_class' => 'Application\Form\Friends',
            'paginator_class' => 'Application\Paginator\FriendsPaginator',
            'template_prefix' => 'friends',
            'route_prefix' => 'account/friends',
        )
    ),
    'paginators' => array(
        'doctrine' => array(
            'Application\Paginator\FriendsPaginator' => 'Application\Entity\Friends', // accepts string or array
        ),
    ),
    'VisioCrudModeler' => array(
        'params' => array(
            'author' => 'VisioCrudModeler',
            'copyright' => 'CamClients',
            'project' => 'CamClients',
            'license' => 'MIT',
            'moduleName' => 'Crud',
            'adapterServiceKey' => '\Zend\Db\Adapter\Adapter',
            'descriptor' => 'db'
        ),
        'generators' => array(
            'controller' => array(
                'adapter' => 'Application\Extended\VisioCrudModeler\Generator\ControllerGenerator',
            ),
            'form' => array(
                'adapter' => 'Application\Extended\VisioCrudModeler\Generator\FormGenerator',
            ),
            'view' => array(
                'adapter' => 'Application\Extended\VisioCrudModeler\Generator\ViewGenerator',
            ),
            'model' => array(
                'adapter' => 'Application\Extended\VisioCrudModeler\Generator\ModelGenerator',
            ),
            'inputFilter' => array(
                'adapter' => 'Application\Extended\VisioCrudModeler\Generator\InputFilterGenerator',
            ),
        )

    )
);