<?php

namespace PerfectWeb\Core\Factory;

use CgmConfigAdmin\Model\Exception;
use CgmConfigAdmin\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfigGroupFactory extends \CgmConfigAdmin\Model\ConfigGroupFactory
{
    /** @inheritdoc */
    public function createConfigGroups(ServiceLocatorInterface $services, ModuleOptions $options, $context = 'site.cfg')
    {

        $filterFunction = function($key) use ($context) {
            return preg_match('/'.$context.'/i', $key);
        };

        $configOptions = $options->getConfigOptions();
        $configGroups = $options->getConfigGroups();

        if (empty(preg_replace('/[\.a-z_-]/i', '', $context))) { // is not empty cause is regex probably

            $configOptions = array_filter($configOptions, $filterFunction, ARRAY_FILTER_USE_KEY);
            $configGroups = array_filter($configGroups, $filterFunction, ARRAY_FILTER_USE_KEY);

            if (!isset($configOptions[$context])) {
                throw new Exception\DomainException(
                    sprintf('Config Options context not found (%s)', $context)
                );
            }
            if (!isset($configGroups[$context])) {
                throw new Exception\DomainException(
                    sprintf('Config Groups context not found (%s)', $context)
                );
            }

            $configOptions = $configOptions[$context];
            $configGroups  = $configGroups[$context];

        }
        else { // flatten the configGroups

            $flatten = function(array $array) {
                $return = [];
                foreach ($array as $value) {
                    foreach ($value as $key => $_value) {
                        $return[$key] = array_merge((array)$return[$key], $_value);
                    }
                }
                return $return;
            };

            $configOptions = $flatten($configOptions);
            $configGroups  = $flatten($configGroups);

        }

        $groups = array();
        foreach ($configGroups as $id => $group) {
            $configGroup = $services->get('cgmconfigadmin_configgroup');
            $configGroup->setId($id)->setOptions($group);
            $groups[$id] = $configGroup;
        }
        if (empty($groups)) {
            $configGroup = $services->get('cgmconfigadmin_configgroup');
            $configGroup
                ->setId('default')
                ->setOptions(array(
                    'label' => 'Settings',
                ));
            $groups['default'] = $configGroup;
        }

        foreach ($configOptions as $groupId => $settingsConfig) {
            if (is_int($groupId)) {
                $groupId = 'default';
            }
            if (!array_key_exists($groupId, $groups)) {
                throw new Exception\DomainException(
                    sprintf('Undefined Group ID (%s)', $groupId)
                );
            }
            foreach ($settingsConfig as $settingId => $settingConfig) {
                $configOption = $services->get('cgmconfigadmin_configoption');
                $configOption->setId($settingId)->setOptions($settingConfig);
                $groups[$groupId]->addConfigOption($configOption);
            }
        }

        return $groups;

    }
}
