<?php

namespace PerfectWeb\Core\Factory;

use PerfectWeb\Core\Entity\Resource;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CgmConfigAdmin\Options\ModuleOptions;

class ConfigOptionsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $config = [];
        /* @var $resource \PerfectWeb\Core\Entity\Resource */
        foreach ($serviceLocator->get('em')->getRepository(Resource::class)->findAll() as $resource) {
            $context = $resource->getContext();
            $group = $resource->getGroup();
            $config['config_groups'][$context][$group] = array('label' => $resource->getLabel());
            $config['config_options'][$context][$group][$resource->getName()] = $resource->getOptionConfig();
        }

        return new ModuleOptions($config);

    }

}