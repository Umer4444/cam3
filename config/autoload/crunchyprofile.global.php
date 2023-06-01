<?php
return array(
    'crunchy-profile' => array(
        'image_path' => 'images/profiles',
        'absolute_image_path' => __DIR__ . '/../../public/images/profiles',
        'field_settings' => array(
            'first_name' => array(
                'type' => 'text',
                'label' => 'First Name',
                'enable' => true,
                'editable' => true,
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 128,
                        ),
                    ),
                ),
            ),
            'last_name' => array(
                'type' => 'text',
                'label' => 'Last Name',
                'enable' => true,
                'editable' => true,
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 128,
                        ),
                    ),
                ),
            ),
            'profile_picture' => array(
                'type' => 'image',
                'label' => 'Profile Picture',
                'enable' => true,
                'editable' => true,
                'required' => true,
            ),
            'screen_name' => array(
                'type' => 'text',
                'label' => 'Screen Name',
                'enable' => true,
                'editable' => true,
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 64,
                        ),
                    ),
                ),
            ),
            'about_me' => array(
                'type' => 'textarea',
                'label' => 'About Me',
                'enable' => true,
                'editable' => true,
                'class' => 'span7',
                'rows' => '15',
            ),
        ),
    ),
);