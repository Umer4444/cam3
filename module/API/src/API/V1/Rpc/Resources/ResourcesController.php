<?php

namespace API\V1\Rpc\Resources;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\Entity;

class ResourcesController extends AbstractActionController
{

    use Traits\Ensure;

    public function resourcesAction()
    {

        /** @var \PerfectWeb\Core\Service\ConfigAdmin $cfg */
        $cfg = $this->getServiceLocator()->get('cfg');
        $params = $this->bodyParams();

        /** @var \Application\Entity\User $user */
        $user = $this->ensureUser($cfg->getUserId());
        $context = $cfg->getContextKey().'.'.$user->getId();

        if ($this->getRequest()->isDelete()) {

            $this->getEntityManager()
                 ->remove(
                     $this->getEntityManager()
                          ->getRepository(Entity\Resource::class)
                          ->findOneBy(['group' => $params['group'], 'name' => $params['name'], 'context' => $context])
                 );
            $this->getEntityManager()->flush();

            return new JsonModel(['status' => 'ok']);
        }

        if (isset($params['label'])) {

            $setting = new Entity\ResourceValue;
            $setting->setValue($params['value']);
            $setting->setUser($user);

            $resource = new Entity\Resource;
            $resource->setContext($context); // make context explicitly for this user
            $resource->setGroup($params['group']);
            $resource->setLabel($params['label']);
            $resource->setName($params['name']);
            $resource->addValue($setting);

            $this->getEntityManager()->persist($resource);
            $status = $this->getEntityManager()->flush();

        }
        else {

            $values = [$params['name'] => $params['value']];

            if (isset($params['context'])) {
                $cfg = $this->getServiceLocator()->get($params['context']);
            }

            $cfg->setLimitToGroup($params['group']);
            $cfg->getConfigOptionsForm()->remove('csrf');


            $status = $cfg->saveConfigValues([$params['group'] => $values]);

        }

        return new JsonModel(['status' => $status]);

    }

}