<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BannersRepository
 * @package Application\Repository
 */
class BannersRepository extends EntityRepository
{
    //$$this->createQueryBuilder('u')
    /**
     * @param $modelId
     * @return array
     */
    public function getBannersModel($modelId)
    {

        // First get the EM handle
        // and call the query builder on it
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u.id, u.content, u.start_date, u.end_date, u.status')
            ->from('\Solo\Entity\Banners', 'u')
            ->where('u.id_owner = :modelId')
            ->andWhere('u.status = 1')
            ->andWhere('u.start_date <= :today')
            ->andWhere('u.end_date >= :today')
            ->setParameters(array(
                'today' => time(),
                'modelId'=> $modelId
        ));

        return $qb->getQuery()->getResult();
    }

}