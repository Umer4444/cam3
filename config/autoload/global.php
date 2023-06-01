<?php
return array(
    'email_settings' => [
        'default_email_sender' => 'no-reply@xexposed.com',
        'default_email_receiver' => 'office@xexposed.com'
    ],
    'zfcadmin' => [
        'use_admin_layout' => true,
        'admin_layout_template' => 'layout/backend',
    ],
    'zfctwig' => array(
        'environment_options' => [
            'autoescape' => false,
        ],
    ),
    'eye4web' => [
        'warnings-ban' => [
            'warningsForBan' => 10,
        ]
    ]
);