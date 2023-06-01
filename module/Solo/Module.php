<?php

namespace Solo;

use Application\Entity\User;
use Application\Mapper\Injector;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;

/**
 * Class Module
 * @package Solo
 */
class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {

        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        $request = $app->getRequest();

        if($request instanceof \Zend\Http\Request) {

            $domain = $request->getServer()->get('HTTP_HOST');
            $user = $sm->get('em')->getRepository(\Application\Entity\User::class)
                                  ->getUserBySettings('domain', $domain);


            if ($user instanceof User) {
                // insert into request in order to setup the helpers with the user
                $request->getQuery()->set(Injector::USER, $user->getId());
            }
            else {
                throw new \Exception(
                    sprintf('The website %s does not correspond to any user.', $request->getServer()->get('HTTP_HOST'))
                );
            }

        }

    }

    /**
     * @return array
     */
    public function getConfig()
    {

        $config = array();
        $configFiles = glob(__DIR__ . '/config/*.php');
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
