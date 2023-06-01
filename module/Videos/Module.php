<?php
namespace Videos;

use Zend\Stdlib\ArrayUtils;

class Module
{
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
