<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ScheduleRepository
 * @package Application\Repository
 */
class ScheduleRepository extends EntityRepository
{

    /**
     * @param $modelId
     * @return array
     *
     * Gets The model Schedule by modelId
     */
    public function getModelScheduleById($modelId)
    {
        // First get the EM handle
        // and call the query builder on it
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u.date, u.type, u.title, u.description, u.url')
           ->from('Application\Entity\ModelSchedule', 'u')
           ->setParameter('modelId', $modelId)
           ->where('u.id_model = :modelId')
           ->andWhere('u.date IS NOT NULL')
           ->orderBy('u.date');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $modelId
     * @return array
     *
     * Gets The model Schedule by modelId
     */
    public function getNextShow()
    {
        // First get the EM handle
        // and call the query builder on it
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u.date')
            ->from('Application\Entity\ModelSchedule', 'u')
            ->where('u.date >= CURRENT_TIMESTAMP()' )
            ->orderBy('u.date', 'ASC')
            ->setMaxResults(1);

        return $qb->getQuery()->getResult();
    }

}