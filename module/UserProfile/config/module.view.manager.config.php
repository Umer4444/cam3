<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            "user-profile" => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
            'ViewPhpRendererStrategy',
        )
    )
);