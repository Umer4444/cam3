<?php

// Composer autoloading
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $loader = include __DIR__ . '/vendor/autoload.php';
}

Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true,

        'namespaces' => array(

            // lazy loading module only
            'ZFMLL' => __DIR__ . '/vendor/blanchonvincent/zf2-lazy-loading-module/ZFMLL',
            'Application' => __DIR__ . '/module/Application/src/Application',

            // tests namespaces
            'Tests' => __DIR__ . '/tests',

        ),

    )
));

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
