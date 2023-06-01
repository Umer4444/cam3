<?php
return array(

    'assetic_configuration' => array(

        'routes' => [
            '(.*)' => [
                '@head_app_js',
                '@head_app_css',
            ],
            'zfcadmin/store/magento' => [
                '@head_store_css',
                '@head_store_js',
            ],
        ],

        'modules' => array(

            'Application' => array(

                'root_path' => __DIR__ . '/../assets',

                'collections' => array(

                    'head_app_js' => array(
                        'assets' => array(
                            '../../../vendor/javascript/FlipClock/compiled/flipclock.js',
                            'js/app.js',
                            'js/socket.js',
                            'js/button.js',
                            'js/im.js',
                            'js/custom.js',
                            'js/context-menu.js',
                            // js fixes
                            'js/bs2-to-3-fix.js',
                            'bought/flowplayer.commercial-5.5.2/flowplayer.min.js',
                            'js/jquery.slideControl.js',
                            'js/flipclock.js',
                        ),
                        'filters' => array(
                            '?JSMinFilter' => array(
                                'name' => 'Assetic\Filter\JSMinFilter'
                            ),
                        ),
                    ),

                    'chat_js' => array(
                        'assets' => array(
                            'js/chat.js',
                            '../../../vendor/javascript/emojione/lib/js/emojione.min.js',
                            '../../../vendor/javascript/Caret.js/src/jquery.caret.js',
                            '../../../vendor/javascript/jquery.atwho/dist/js/jquery.atwho.min.js',
                            '../../../vendor/javascript/bootstrap-multiselect/dist/js/bootstrap-multiselect.js',
                            '../../../vendor/javascript/jquery-stylesheet/jquery.stylesheet.js',
                        ),
                        'filters' => array(
                            '?JSMinFilter' => array(
                                'name' => 'Assetic\Filter\JSMinFilter'
                            ),
                        ),
                    ),

                    'chat_css' => array(
                        'assets' => array(
                            'css/chat.css',
                            '../../../vendor/javascript/emojione/assets/css/emojione.min.css',
                            '../../../vendor/javascript/jquery.atwho/dist/css/jquery.atwho.min.css',
                            '../../../vendor/javascript/bootstrap-multiselect/dist/css/bootstrap-multiselect.css',
                        ),
                    ),

                    'head_app_css' => array(
                        'assets' => array(
                            'css/chat.css',
                            'css/im.css',
                            'css/style.css',
                            'css/style_signup.css',
                            'css/style_new.css',
                            'css/flags.css',
                            'css/app.css',
                            'css/flags.css',
                            'css/theme_overwrite.css',
                            'css/overwrites.css',
                            'css/overwrites.css',
                            'css/jquery.mCustomScrollbar.css',
                            'bought/flowplayer.commercial-5.5.2/skin/minimalist.css',
                            'css/flipclock.css',
                            'css/bs-overwrites.css',
                            'css/popup.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),

                    'head_fix_css' => array(
                        'assets' => array(
                            'css/head-fix.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),
                    'head_store_css' => array(
                        'assets' => array(
                            'css/store.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),

                    'head_store_js' => array(
                        'assets' => array(
                            'js/store.js',
                        ),
                        'filters' => array(
                            '?JSMinFilter' => array(
                                'name' => 'Assetic\Filter\JSMinFilter'
                            ),
                        ),
                    ),

                    'static' => array(
                        'assets' => array(
                            'sounds/*',
                            'defaults/images/*',
                            'defaults/fonts/*',
                            'defaults/users/*',
                            'images/*',
                            'images/icons/*',
                            'images/social/*',
                            'images/badges/*',
                            'images/animated/*',
                            'images/game/*',
                            'bought/flowplayer.commercial-5.5.2/skin/img/*',
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