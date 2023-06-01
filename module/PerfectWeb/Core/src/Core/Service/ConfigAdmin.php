<?php

namespace PerfectWeb\Core\Service;

use CgmConfigAdmin\Model\ConfigGroup;
use CgmConfigAdmin\Model\ConfigOption;
use Application\Entity\User;
use CgmConfigAdmin\Service\ConfigAdmin as CgmConfigAdmin;
use CgmConfigAdmin\Service\Exception;
use Doctrine\Common\Collections\Criteria;
use PerfectWeb\Core\Traits;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use PerfectWeb\Core\Entity\ResourceValue;
use PerfectWeb\Core\Entity\Resource;

class ConfigAdmin extends CgmConfigAdmin implements ServiceLocatorAwareInterface
{

    use Traits\EntityManager;

    var $limitToGroup = [];

    function getUserId()
    {
        return $this->userId;
    }

    /**
     * @inheritdoc
     */
    function getContextKey()
    {
        return $this->context;
    }

    public function getConfigPossibleValues($groupId, $optionId = null)
    {
        // Support optional format 'groupName\optionName'
        if (!isset($optionId)) {
            $parts = preg_split('/\//', $groupId);
            list($groupId, $optionId) = $parts;
        }

        $configGroups = $this->getConfigGroups();
        if (isset($configGroups[$groupId])
            && $configGroups[$groupId]->hasConfigOption($optionId)
        ) {
            return $configGroups[$groupId]
                ->getConfigOption($optionId)
                ->prepare()->getValueOptions();
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Config Values does not exist with the $groupId/$optionId combination (%s/%s) in context %s',
            $groupId, $optionId, $this->getContextKey()
        ));
    }

    public function setConfigValue($groupId, $values, $optionId = null)
    {
        // Support optional format 'groupName\optionName'
        if (!isset($optionId)) {
            $parts = preg_split('/\//', $groupId);
            list($groupId, $optionId) = $parts;
        }

        $configGroups = $this->getConfigGroups();
        if (isset($configGroups[$groupId]) && $configGroups[$groupId]->hasConfigOption($optionId)) {

            $resource = $this->getEntityManager()
                             ->getRepository(Resource::class)
                             ->findOneBy(['group' => $groupId, 'name' => $optionId]);

            $contextUser = $this->getUserId() ? $this->getEntityManager()->find(\Application\Entity\User::class, $this->getUserId()) : null;

            // existent values
            $settings = $resource->getValues()
                                 ->matching(
                                     Criteria::create()
                                             ->where(
                                                 $this->getUserId() ?
                                                     Criteria::expr()->eq('user', $contextUser) :
                                                     Criteria::expr()->isNull('user')
                                             )
                                 );

            $values = is_array($values) ? $values : [$values];
            $existingEntities = [];

            foreach ($values as $value) {

                $valueCheck = $value;
                if ($value instanceof ResourceValue) {
                    $value->setResource($resource);
                    if (!$value->getUser()) {
                        $value->setUser($contextUser);
                    }
                    $valueCheck = $value->getValue();
                }

                $found = false;
                foreach ($settings as $setting) {
                    if ($setting->getValue() == $valueCheck) {
                        $found = true;
                        $existingEntities[$setting->getId()] = true;
                        break;
                    }
                }

                if (!$found && ($value instanceof ResourceValue)) {
                    $this->getEntityManager()->persist($value);
                }
                elseif (!$found) {
                    $setting = new ResourceValue();
                    $setting->setUser($contextUser);
                    $setting->setResource($resource);
                    $setting->setValue($value);
                    $this->getEntityManager()->persist($setting);
                }
                else {
                    $setting->setValue($value);
                }

            }

            foreach ($settings as $setting) {
                if (!isset($existingEntities[$setting->getId()])) {
                    $this->getEntityManager()->remove($setting);
                }
            }

            return $this->getEntityManager()->flush();

        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Config Values does not exist with the $groupId/$optionId combination (%s/%s) in context %s',
            $groupId, $optionId, $this->getContextKey()
        ));
    }

    /**
     * @inheritdoc
     */
    public function getConfigOptionsForm()
    {
        if (!$this->configOptionsForm) {
            /** @var ConfigOptionsForm $form */
            $form = $this->getServiceManager()->get('cgmconfigadmin_form');
            $form->setIsPreviewEnabled($this->isPreviewEnabled());
            $form->addConfigGroups($this->getConfigGroups());
            $this->setConfigOptionsForm($form);
        }

        return $this->configOptionsForm;
    }

    /**
     * @inheritdoc
     */
    public function getConfigGroups($limitToGroup = null)
    {

        if (!is_null($limitToGroup)) {
            $this->setLimitToGroup($limitToGroup);
        }

        $limitToGroup = $this->getLimitToGroup();

        $factory = $this->getServiceManager()->get('cgmconfigadmin_configgroupfactory');
        $groups  = $factory->createConfigGroups(
            $this->getServiceManager(), $this->getOptions(), $this->context
        );

        if (!empty($limitToGroup)) {
            /** @var ConfigGroup $group */
            foreach ($groups as $index => $group) {
                if (!in_array($index, $limitToGroup)) {
                    unset($groups[$index]);
                }
            }
        }

        $contextKey = $this->getContextKey();

        // Apply from data store
        $configValue = $this->getConfigValuesMapper()->find($this);

        if (!empty($configValue)) {

            /** @var ConfigGroup $group */
            foreach ($groups as $group) {

                /** @var ConfigOption $option */
                foreach ($group->getConfigOptions() as $option) {

                    $valueArray = []; // for multicheckbox
                    $found = false;

                    /** @var \PerfectWeb\Core\Entity\ResourceValue $value */
                    foreach ($configValue as $value) {
                        if (
                            !$value ||
                            $value->getResource()->getGroup().'_'.$value->getResource() != $option->getUniqueId()
                        ) {
                            continue;
                        }
                        $valueArray[] = $value->getValue();
                        $found = true;
                    }

                    if ($option->getInputType() != 'multicheckbox') {
                        $valueArray = current($valueArray);
                    }

                    $option->setValue($found ? $valueArray : $option->getValue());

                }

            }

        }

        // Apply from session
        $values = $this->getSession()->$contextKey;
        if ($values) {
            $this->applyValuesToConfigGroups($values, $groups);
        }

        $this->setConfigGroups($groups);

        if (!empty($limitToGroup)) {

            $groups = [];
            foreach ($limitToGroup as $_limitToGroup) {
                $groups[$_limitToGroup] = $this->configGroups[$_limitToGroup];
            }
            $this->configGroups = $groups;

        }

        return $this->configGroups;

    }

    public function getConfigGroup($limitToGroup = [])
    {
        return current($this->getConfigGroups($limitToGroup));
    }

    /**
     * @inheritdoc
     */
    public function saveConfigValues($config)
    {

        $form = $this->getConfigOptionsForm();
        $form->setData($config);
        if (!$form->isValid()) {
            return false;
        }

        $config = $form->getData();

        $configGroups = $this->getConfigGroups();
        $this->applyValuesToConfigGroups($config, $configGroups);

        $contextKey = $this->getContextKey();
        $configValues = $this->getConfigValuesMapper()->find($this) ?: [];

        /** @var ConfigGroup $group */
        foreach($configGroups as $group) {

            /** @var ConfigOption $option  */
            foreach ($group->getConfigOptions() as $option) {

                if ($option->hasValueChanged()) {

                    $found = false;
                    /** @var \PerfectWeb\Core\Entity\ResourceValue $value */
                    foreach ($configValues as $value) {

                        if ($value->getResource()->getGroup().'_'.$value->getResource()->getName() != $option->getUniqueId()) {
                            continue;
                        }

                        $value->setValue($option->getValue());
                        $found = true;

                    }

                    if (!$found) {

                        $value = new \PerfectWeb\Core\Entity\ResourceValue();
                        $value->setResource(
                            $this->getServiceManager()
                                 ->get('em')
                                 ->getRepository(\PerfectWeb\Core\Entity\Resource::class)
                                 ->findOneBy(['group' => $option->getGroupId(), 'name' => $option->getId()])
                        );
                        $value->setUser(
                            !$this->getUserId() ? null :
                                $this->getServiceManager()->get('em')->find(User::class, $this->getUserId())
                        );
                        $value->setValue($option->getValue());

                        $configValues[] = $value;

                    }

                }

            }

        }

        /** @var \Zend\EventManager\ResponseCollection $eventResponse */
        $eventResponse = $this->getEventManager()->trigger(
            __FUNCTION__, $this, array('configValues' => $configValues)
        );

        if (!$eventResponse->stopped()) {

            $this->getConfigValuesMapper()->save($configValues);
            unset($this->getSession()->$contextKey);

        }

        $this->getEventManager()->trigger(
            __FUNCTION__.'.post', $this, array('configValues' => $configValues)
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getConfigValue($groupId, $optionId = null)
    {

         // Support optional format 'groupName/optionName'
        if (!isset($optionId)) {
            $parts = preg_split('/\//', $groupId);
            list($groupId, $optionId) = $parts;
        }

        try {
            return parent::getConfigValue($groupId, $optionId);
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return null|array
     */
    public function getLimitToGroup()
    {
        return $this->limitToGroup;
    }

    /**
     * @param $limitToGroup
     *
     * @return $this
     */
    public function setLimitToGroup($limitToGroup)
    {

        if (!is_array($limitToGroup)) {
            $limitToGroup = is_null($limitToGroup) ? [] : explode(',', $limitToGroup);
        }

        $this->limitToGroup = $limitToGroup;
        return $this;
    }

}