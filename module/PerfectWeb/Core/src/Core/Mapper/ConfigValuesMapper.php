<?php

namespace PerfectWeb\Core\Mapper;

use CgmConfigAdmin\Entity\ConfigValuesMapper as CgmConfigValuesMapper;
use PerfectWeb\Core\Service\ConfigAdmin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use PerfectWeb\Core\Traits;

class ConfigValuesMapper extends CgmConfigValuesMapper implements ServiceLocatorAwareInterface
{

    use Traits\EntityManager;

    public function find($configadmin)
    {

        /** @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->getEntityManager()
                   ->getRepository(\PerfectWeb\Core\Entity\Resource::class)
                   ->createQueryBuilder('r');

        $contextParts = explode('.', $configadmin->getContextKey(), 3);
        unset($contextParts[2]);

        $params = ['context' => implode('.', $contextParts)];

        $qb->leftJoin(\PerfectWeb\Core\Entity\ResourceValue::class, 'rv', 'WITH', 'rv.resource = r.id')
           ->select('rv')
           ->where('r.context = :context');

        if ($params['context'] != 'site.cfg') {
            $params['user'] = $configadmin->getUserId();
            $qb->andWhere('rv.user = :user');
        }


        if (!empty($configadmin->getLimitToGroup())) {
            $qb->andWhere('r.group IN (:group)');
            $params['group'] = $configadmin->getLimitToGroup();
        }

        $qb->setParameters($params);

        $result = $qb->getQuery()->getResult();

        return count($result) ? $result : null;

    }

    public function save($configValues)
    {

        foreach ($configValues as $value) {
            if (!$value->getId()) {
                $this->getEntityManager()->persist($value);
            }
        }

        $this->getEntityManager()->flush();

    }

}
