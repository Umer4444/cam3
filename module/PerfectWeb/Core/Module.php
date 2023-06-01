<?php

namespace PerfectWeb\Core;

use Zend\Mvc\MvcEvent;
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
                    __NAMESPACE__ => __DIR__ . '/src/Core',
                ),
            ),
        );
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'));
    }

    /**
     * on render event
     *
     * @param MvcEvent $e
     */
    public function onRender(MvcEvent $e)
    {

        if(php_sapi_name() != 'cli' && $e->getRouteMatch()) {

            // merge route params with query params which may be added on request
            $params = array_merge($e->getRequest()->getQuery()->getArrayCopy(), $e->getRouteMatch()->getParams());

            foreach ($params as $param => $value) {

                list($check, $var, $key, $entity) = explode('_', $param);
                if ($check != 'entity' || empty($key) || !class_exists($entity)) {
                    continue;
                }

                $em = $e->getApplication()->getServiceManager()->get('em');
                $result = $em->getRepository($entity)->findOneBy([$key => $value]);

                if (!$e->getViewModel()->getVariable($var)) {
                    $e->getViewModel()->setVariable($var, $result);
                }
                // assign the variables to the child view models as well
                foreach($e->getViewModel()->getChildren() as $child) {
                    /** @var \Zend\View\Model\ViewModel $child */
                    if (!$child->getVariable($var)) {
                        $child->setVariable($var, $result);
                    }
                }

            }

        }

    }

    public function getModuleDependencies()
    {
        return array(
            'DoctrineORMModule',
            'ZfcUser',
            'AcMailer',
            'BjyAuthorize',
        );
    }

}
