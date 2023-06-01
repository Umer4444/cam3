<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BlogPostsRepository
 * @package Application\Repository
 */
class BlogPostsRepository extends EntityRepository

{
    /**
     * @param null $userId
     * @param null $type
     * @return bool
     */
    public function getPostsWithDate($userId = null, $type = 'none') {


        if ($type != 'guest') $type = $type->getRoleId();
        if (!$userId) return false;
        if (!$type) $type = 'none';
        if ($type == 'member') $type = 'members';
        // First get the EM handle
        // and call the query builder on it

        $qb = $this->getPostsWithDateBuilder($userId, $type);
        if($qb) {

            return $qb->getQuery()->getResult();
        } else {

            return false;
        }


    }

    /**
     * @param null $userId
     * @param null $type
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getPostsWithDateBuilder($userId = null, $type = 'none', $offset, $limit) {


        if(!$userId) return false;
        if ($type == 'member') $type = 'members';
        // First get the EM handle
        // and call the query builder on it
        $qb = $this->createQueryBuilder('u');
        $qb->select(array('DISTINCT u', 'a.type', 'a.date', 'a.chips'))
            ->join('Application\Entity\BlogAccess', 'a', 'WITH', 'a.post = u.id')
            ->where("u.user = :user")
            ->andWhere('u.status = 1')
            ->andWhere('a.type IN (:types)')
            ->andWhere('a.date <= :now')
            ->groupBy('u.id')
            ->orderBy('u.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameters(array(
                'user' => $userId,
                'types' => array('everyone',$type),
                'now' => time()
            ));

        return $qb->getQuery()->getScalarResult();

    }

    /**
     * @param $itemsPerPage
     * @param null $userId
     * @param string $type
     * @return mixed
     */
    public function countPosts($itemsPerPage, $userId = null, $type = 'everyone')
    {

        if (!$itemsPerPage) return false;
        if (!$userId) return false;

        $qb = $this->createQueryBuilder('u');
        $qb->select(array('sum(distinct u.id)'))
            ->join('Application\Entity\BlogAccess', 'a', 'WITH', 'a.post = u.id')
            ->where("u.user = :user")
            ->andWhere('u.status = 1')
            ->andWhere('a.type IN (:types)')
            ->andWhere('a.date <= :now')
            ->groupBy('u.id')
            ->setParameters(array(
                'user' => $userId,
                'types' => array('everyone',$type),
                'now' => time()
            ));

        return count($qb->getQuery()->getScalarResult());

    }

    public function getCategoryFilter() {

        $qb = $this->createQueryBuilder('b');
        $qb->select('b');

        return $qb;
    }

}