<?php

// @todo this needs to be done recursive for each solo
return array(

    'assetic_configuration' => array(

        'routes' => [
            '(.*)\/(?!admin)' => [ // dont add it to the admin section
                '@head_solo_css',
            ],
        ],

        'modules' => array(

            'Solo' => array(

                'root_path' => getcwd(),

                'collections' => array(

                    'static' => array(
                        'assets' => array(
                            'themes/anakaliyah.com/assets/images/*',
                            'themes/anakaliyah.com/assets/css/*',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        ),
                    ),

                ),
            ),

        ),

    ),
);