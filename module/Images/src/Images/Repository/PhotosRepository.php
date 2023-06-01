<?php
namespace Images\Repository;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Doctrine\ORM\Query\Expr;

/**
 * Class PhotosRepository
 * @package Images\Repository
 */
class PhotosRepository extends SortableRepository
{
    /**
     * @param null $id
     * @param bool $active
     * @return mixed
     */
    public function getItems($active = false, $id = null, $offset = 0, $limit = 30)
    {

        $select = $this->createQueryBuilder('p')->select('p, m');

        $select
            /*->where('p.type = :photo')
            ->setParameter('photo', 'photo')*/
            ->where('p.type is null')
            ->orderBy("p.position","asc");

        $select->leftJoin('Application\Entity\User','m', Expr\Join::WITH, 'm.id = p.user' );

        if ($id) $select->andWhere("p.user = :uid")->setParameter("uid", (int)$id);

        if (!is_null($active) && $active) {
            $select->andWhere("p.status = :active")->setParameter('active', $active);
        }

        if (false === is_null($offset))
            $select->setFirstResult($offset);

        if (false === is_null($limit))
            $select->setMaxResults($limit);

        $select->groupBy("p.id");
        return $select->getQuery();
    }

    public function countItems($active = false, $id = null)
    {

        $select = $this->createQueryBuilder('p')->select('count(p.id)');

        $select
            ->where('p.type = :photo')
            ->setParameter('photo', 'photo');

        $select->join('Application\Entity\User','m', Expr\Join::WITH, 'm.id = p.user' );
        if ($id)
            $select->andWhere("p.user=?", (int)$id);

        if ($active) {
            $select->andWhere("p.status= :active")->setParameter('active', $active);
        }

        return $select->getQuery()->getSingleScalarResult();
    }
}