<?php

$modules = array(
    'ZfcAdmin',
    'ZfcUser',
    'ZfcBase',
    'BjyAuthorize',

    'AsseticBundle',
    'ZfcTwitterBootstrap',
    'DoctrineModule',
    'DoctrineORMModule',
    'Phpro\DoctrineHydrationModule',

    'ZfcTwig',
    'ZfcUserDoctrineORM',
    'Zf2SlugGenerator',
    'ZfTwitterWidget',
    'AcMailer',
    'Eye4web\ZfcUser\Warnings',
    'Eye4web\ZfcUser\WarningsBan',

    // admin modules
    'CgmConfigAdmin',

    // Core module
    'PerfectWeb\Core',
    'PerfectWeb\Payment',
    'Application',
    'BitWeb\CronModule',

    // profile related
    'OrgHeiglContact',
    'UserProfile',
    'UserAccount',
    'ZfcUserImpersonate',

    // apigility
    'ZF\Apigility',
    'ZF\Apigility\Provider',
    'ZF\ApiProblem',
    'ZF\MvcAuth',
    'ZF\Hal',
    'ZF\ContentNegotiation',
    'ZF\ContentValidation',
    'ZF\Rest',
    'ZF\Rpc',
    'ZF\Versioning',
    'ZF\DevelopmentMode',
    'ZF\Apigility\Documentation',
    'ZF\Apigility\Documentation\Swagger',
    'ZF\Apigility\Doctrine\Server',
    //'ZF\Doctrine\QueryBuilder',
    'API',

    // custom made modules
    'Videos',
    'Images',
    'Interactions',

    // CRUD
    'Nicovogelaar\Paginator',
    'ZfTable',
    'VisioCrudModeler',
    'Crud',

    // others
    'RbComment',
    'Payum\PayumModule',
    'ZFTool',
    'ZfSnapGeoip'

);

if (getenv('APPLICATION_ENV') == 'development') {
    require_once __DIR__ . '/../vendor/digitalnature/php-ref/ref.php';
}

if (getenv('APPLICATION_TYPE') == 'solo') {
    define('APPLICATION_TYPE', 'solo');
    $modules[] = 'Solo';
}
else {
    define('APPLICATION_TYPE', 'main');
}

return array(

    'modules' => $modules,

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor'
        ),
    ),
);