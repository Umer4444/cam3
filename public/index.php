<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define('ROOT_PATH', dirname(__DIR__));
date_default_timezone_set("UTC");

//include functions
// @todo moreve from here asap !!!
require_once(__DIR__ . '/../data/functions.php');

// Setup autoloading

include 'init_autoloader.php';

$config = include 'config/application.config.php';
$soloStore = 'config/' . $_SERVER['HTTP_HOST'] . '.modules.php';
if (file_exists($soloStore)) {
    $config = \Zend\Stdlib\ArrayUtils::merge($config, include $soloStore);
}

$devConfig = 'config/development.config.php';
if (file_exists($devConfig)) {
    $config = Zend\Stdlib\ArrayUtils::merge(
        $config,
        include $devConfig
    );
}

// Run the application!
Zend\Mvc\Application::init($config)->run();
