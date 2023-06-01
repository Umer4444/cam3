<?php

namespace PerfectWeb\Payment;

use Zend\Stdlib\ArrayUtils;

class Module
{

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
                    __NAMESPACE__ => __DIR__ . '/src/Payment',
                ),
            ),
        );
    }

    public function getModuleDependencies()
    {
        return array(
            'PerfectWeb\Core',
        );
    }

}
