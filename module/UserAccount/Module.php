<?php

namespace UserAccount;

use Zend\Stdlib\ArrayUtils;

/**
 * Class Module
 * @package UserAccount
 */
class Module
{

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = array();
        $configFiles = glob(__DIR__ . '/config/*.config.php');
        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
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