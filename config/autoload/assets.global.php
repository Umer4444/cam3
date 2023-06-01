<?php

$jqueryUi = 'ui-lightness';

$settings = array(

    'assetic_configuration' => array(

        'buildOnRequest' => false,
        'webPath' => 'public/assets',
        'cachePath' => 'data/cache',
        'basePath' => 'assets',
        'combine' => true,

        'default' => array(
            'assets' => array(
                '@head_bare_js',
                '@head_bare_css',
                '@head_base_js',
                '@head_base_css',
                '@head_deprecated_css',
            ),
            'options' => array(
                'mixin' => true
            ),
        ),

        'routes' => [
            'zfcadmin/(.*)' => [
                '@head_admin_js',
                '@head_admin_css',
            ],
            '(zfcadmin\/manage\/profile\-page|performer\/profile)' => [
                '@head_gridstack_js',
                '@head_gridstack_css',
            ],
        ],

        'modules' => array(

            'AsseticBundle' => array(

                'root_path' => getcwd(),

                'collections' => array(

                    'head_bare_js' => array(
                        'assets' => array(
                            'vendor/javascript/jquery/dist/jquery.min.js',
                            'vendor/javascript/jquery-ui/jquery-ui.js',
                            // keep after jquery-ui, there are some strange conflicts
                            'vendor/javascript/bootstrap/dist/js/bootstrap.min.js',
                        ),
                    ),

                    //@TODO remove from global as this is a temporary fix for solo assets
                    'head_solo_css' => array(
                        'assets' => array(
                            'vendor/javascript/bootstrap/dist/css/bootstrap.min.css',
                            'themes/anakaliyah.com/assets/css/bootstrap3-overwrite.css',
                            'themes/anakaliyah.com/assets/css/style.css'
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),

                    'head_base_js' => array(
                        'assets' => array(
                            'vendor/javascript/typeahead.js/dist/typeahead.jquery.min.js',
                            'vendor/javascript/jcarousel/dist/jquery.jcarousel.min.js',
                            'vendor/javascript/jquery.select-to-autocomplete/jquery.select-to-autocomplete.js',
                            'vendor/javascript/jquery.serializeJSON/jquery.serializejson.min.js',
                            'vendor/javascript/jquery-validation/dist/jquery.validate.min.js',
                            'vendor/javascript/fancybox/source/jquery.fancybox.pack.js',
                            'vendor/javascript/fancybox/lib/jquery.mousewheel-3.0.6.pack.js',
                            'vendor/javascript/jScrollPane/script/jquery.jscrollpane.min.js',
                            'vendor/javascript/jquery-cookie/jquery.cookie.js',
                            'vendor/javascript/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js',
                            'vendor/javascript/jquery-tokeninput/build/jquery.tokeninput.min.js',
                            'vendor/javascript/metisMenu/dist/metisMenu.min.js',
                            'vendor/javascript/eventCalendar/js/moment.js',
                            'vendor/javascript/eventCalendar/js/jquery.eventCalendar.min.js',
                            'vendor/javascript/jQuery-contextMenu/src/jquery.contextMenu.js',
                            'vendor/javascript/uploadify/jquery.uploadify.js',
                            'vendor/javascript/jQuery.serializeObject/dist/jquery.serializeObject.min.js',
                            'vendor/javascript/jQuery-contextMenu/src/jquery.contextMenu.js',
                            'vendor/javascript/DataTables/media/js/jquery.dataTables.min.js',
                            'vendor/javascript/jquery.ui.chatbox/jquery.ui.chatbox.js',
                            'vendor/javascript/galleria/src/galleria.js',
                            'vendor/javascript/jquery-idleTimeout/jquery-idleTimeout.js',
                            'vendor/javascript/social-likes/dist/social-likes.min.js',
                            'vendor/javascript/notifyjs/dist/notify.min.js',
                            'vendor/javascript/notifyjs/dist/styles/bootstrap/notify-bootstrap.js',
                            'vendor/javascript/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js',
                            'vendor/javascript/bootstrap-timepicker/js/bootstrap-timepicker.js',
                            'vendor/javascript/FlipClock/compiled/flipclock.min.js',
                            'vendor/javascript/countto/jquery.countTo.js',
                            'vendor/javascript/jQuery-vGrid-Plugin/jquery.vgrid.min.js',
                            'vendor/dudapiotr/zftable/src/ZfTable/Public/js/zf-table.js',
                            'vendor/javascript/fastclick/lib/fastclick.js',
                            'vendor/javascript/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
                            'nodejs/node_modules/socket.io/node_modules/socket.io-client/socket.io.js',
                            'module/Application/assets/js/jquery.ddslick.min.js', //@todo remove
                            'module/Application/assets/js/function.js', //@todo remove
                            'module/Application/assets/js/images.js'
                        ),
                        'filters' => array(
                            '?JSMinFilter' => array(
                                'name' => 'Assetic\Filter\JSMinFilter'
                            ),
                        ),
                    ),

                    'head_admin_js' => array(
                        'assets' => array(
                            'vendor/javascript/tinymce-dist/jquery.tinymce.min.js',
                            'vendor/javascript/bootstrap-multiselect/dist/js/bootstrap-multiselect.js',
                            'vendor/javascript/webcam/jquery.webcam.js',
                        ),
                    ),

                    'head_admin_css' => array(
                        'assets' => array(
                            'module/Application/assets/css/admin.css',
                            'vendor/javascript/bootstrap-multiselect/dist/css/bootstrap-multiselect.css',
                        ),
                    ),

                    'head_gridstack_js' => array(
                        'assets' => array(
                            'vendor/javascript/underscore/underscore-min.js',
                            'vendor/javascript/gridstack/dist/gridstack.min.js',
                        ),
                    ),

                    'head_gridstack_css' => array(
                        'assets' => array(
                            'vendor/javascript/gridstack/dist/gridstack.min.css',
                        ),
                    ),

                    'head_base_css' => array(
                        'assets' => array(
                            'vendor/javascript/jquery-ui/themes/'.$jqueryUi.'/jquery-ui.min.css',
                            'vendor/javascript/jquery-ui/themes/'.$jqueryUi.'/theme.css',
                            'vendor/javascript/bootstrap/dist/css/bootstrap.min.css',
                            'vendor/javascript/fancybox/source/jquery.fancybox.css',
                            'vendor/javascript/jScrollPane/style/jquery.jscrollpane.css',
                            'vendor/javascript/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css',
                            'vendor/javascript/jquery-tokeninput/styles/token-input.css',
                            'vendor/javascript/metisMenu/dist/metisMenu.css',
                            'vendor/javascript/uploadify/uploadify.css',
                            'vendor/javascript/jQuery-contextMenu/src/jquery.contextMenu.css',
                            'vendor/javascript/jquery.ui.chatbox/jquery.ui.chatbox.css',
                            'vendor/javascript/galleria/src/themes/classic/galleria.classic.css',
                            'vendor/javascript/eventCalendar/css/eventCalendar.css',
                            'vendor/javascript/FlipClock/compiled/flipclock.css',
                            'vendor/javascript/fontawesome/css/font-awesome.min.css',
                            'vendor/javascript/social-likes/dist/social-likes_classic.css',
                            'vendor/javascript/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',
//                            'vendor/javascript/eventCalendar/css/eventCalendar_theme.css',
//                            'vendor/javascript/eventCalendar/css/eventCalendar_theme_responsive.css',
                            // check this
                            'vendor/dudapiotr/zftable/src/ZfTable/Public/css/style.css',
                            'vendor/dudapiotr/zftable/src/ZfTable/Public/css/zf-table/zf-table.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),

                    'head_deprecated_css' => array(
                        'assets' => array(
                            'public/themes/default/css/merged_theme_old.css',
                            'public/css/merged_old_css.css',
                        ),
                    ),

                    'head_bare_css' => array(
                        'assets' => array(
                            'vendor/javascript/bootstrap/dist/css/bootstrap.min.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),

                    'play_chess_css' => array(
                        'assets' => array(
                            'vendor/javascript/bootstrap/dist/css/bootstrap.min.css',
                            'nodejs/chess/public/css/styles.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),

                    'play_chess_game_js' => array(
                        'assets' => array(
                            'vendor/javascript/fastclick/lib/fastclick.js',
                            'nodejs/chess/public/js/client.js',
                            'nodejs/node_modules/socket.io/node_modules/socket.io-client/socket.io.js',
                        ),
                    ),

                    'static' => array(
                        'assets' => array(
                            'vendor/javascript/jquery-ui/themes/'.$jqueryUi.'/images/*',
                            'vendor/javascript/bootstrap/dist/fonts/*',
                            'vendor/javascript/fancybox/source/helpers/*',
                            'vendor/javascript/fancybox/source/*.gif',
                            'vendor/javascript/fancybox/source/*.png',
                            'vendor/javascript/fancybox/source/*.png',
                            'vendor/javascript/x-editable/dist/bootstrap3-editable/img/*',
                            'vendor/javascript/jQuery-contextMenu/src/images/*',
                            'vendor/javascript/uploadify/uploadify-cancel.png',
                            'vendor/javascript/uploadify/uploadify.swf',
                            'vendor/javascript/fontawesome/fonts/*',
                            'vendor/javascript/jQuery-contextMenu/src/images/*',
                            'vendor/javascript/galleria/src/themes/classic/classic-loader.gif',
                            'vendor/javascript/galleria/src/themes/classic/classic-map.png',
                            'vendor/javascript/galleria/src/themes/classic/galleria.classic.js', // needed as static for initialisation
                            'vendor/dudapiotr/zftable/src/ZfTable/Public/img/datatable/*',
                            'vendor/dudapiotr/zftable/src/ZfTable/Public/img/zf-table/*',
                            'nodejs/chess/public/img/*',
                            'vendor/javascript/webcam/*.swf',
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

return array_merge(

    $settings,

    array(

        // https://github.com/RWOverdijk/AssetManager
        // we do not use this asset_manager but we overwrite the settings for the models that do
        'asset_manager' => array(
            'resolver_configs' => array(
                'paths' => array(
                    $settings['assetic_configuration']['webPath'],
                ),
            ),
        ),

    )
);