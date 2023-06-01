<?php

namespace Images;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {

        $e->getApplication()->getServiceManager()->get('viewhelpermanager')
            ->setFactory('controllerName', function ($sm) use ($e) {
                $viewHelper = new View\Helper\ControllerName($e->getRouteMatch());
                return $viewHelper;
            });
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($e->getApplication()->getEventManager());

    }

    public function getConfig()
    {

        $config = [];

        foreach (glob(__DIR__ . '/config/*.php') as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }

        return $config;

    }

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
