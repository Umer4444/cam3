<?php
return array(

    'view_manager' => array(

        'display_not_found_reason' => false,
        'display_exceptions' => false,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'layout' => 'layout/frontend',

        'strategies' => array(
            'ViewJsonStrategy',
            'ViewPhpRendererStrategy',
        ),

        'template_map' => array(
            'paginator-slide' => __DIR__ . '/../../module/Application/view/templates/slide-paginator.phtml',
            'table-custom' => __DIR__ . '/../../module/Application/view/templates/custom-template.phtml'
        ),

    ),

);