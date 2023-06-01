<?php

namespace Crud;

use Zend\Stdlib\ArrayUtils;

/**
 * Standard Module class
 *
 * @author VisioCrudModeler
 * @project Cam network
 * @license MIT
 * @copyright Cam network
 */
class Module
{

    /**
     * standard bootstrap method
     *
     * used to configure additional features, not available through module.config.php
     *
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new \Zend\Mvc\ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return array
     */
    public function getConfig()
    {

        $config = [];
        foreach (glob(__DIR__ . '/config/*.php') as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }

        return $config;

    }

    /**
     * autoloader configuration
     *
     * used to load module related class
     */
    public function getAutoloaderConfig()
    {
        return array(
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                 )
             )
        );
    }

}

