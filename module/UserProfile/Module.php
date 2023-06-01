<?php

namespace UserProfile;

/**
 * Class Module
 * @package UserProfile
 */
class Module
{

    /**
     * @param $e
     */
    public function onBootstrap($e)
    {}

    /*
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }*/


    /**
     * @param $e
     */
    public function onDispatch($e)
    {}


    /**
     * @return array
     */
    public function getConfig()
    {
        $config = array();
        $configFiles = array(
            __DIR__ . '/config/module.config.php',
            __DIR__ . '/config/module.routes.config.php',
            __DIR__ . '/config/module.service.manager.config.php',
            __DIR__ . '/config/module.view.manager.config.php',
        );
        foreach ($configFiles as $configFile) {
            $config = \Zend\Stdlib\ArrayUtils::merge
            ($config, include $configFile);
        }
        return $config;
    }

    /**
     * @return array
     */

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
