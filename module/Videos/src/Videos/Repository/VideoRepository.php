<?php

namespace Videos\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use PerfectWeb\Core\Utils\Status;

/**
 * Class VideoRepository
 * @package Videos\Repository
 */
class VideoRepository extends EntityRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getPrice($id) {

        $perSecond = $this->_em->getRepository('Application\Entity\Config')
            ->findOneBy(array('var' => 'tokens_per_second'))->getVal();

        $videoDuration = $this->findOneBy(array('id' => $id))->getDuration();

        $price = $perSecond * $videoDuration;

        return $price;

    }

    public function getCategories($id) {

        $categories = $this->_em->getRepository('Application\Entity\VideoToCategories')->findBy(array('videoId' => $id));
        $categoryRepo = $this->_em->getRepository('Application\Entity\Categories');
        $ids = array();
        foreach ($categories as $category) {

            $ids[] = $category->getCategoryId() ;

        }
        $categoryNames = $categoryRepo->findById($ids);

        return $categoryNames;

    }

    /**
     * @param null $id
     * @param bool $active
     * @return mixed
     */
    public function getItems($active = false, $type = null, $id = null, $offset = 1, $limit = 30)
    {

        $select = $this->createQueryBuilder('v')->select('v');


        $select
            ->orderBy("v.uploadedOn", "desc");

        //$select->leftJoin('Application\Entity\User', 'm', Expr\Join::WITH, 'm.id = v.userId');

        if ($id)
            $select->andWhere("v.userId=:uid")->setParameter("uid", (int)$id);

        if ($active) {
            $select->andWhere("v.status= :active")->setParameter('active', $active);
        }
        if(!is_null($type) && $type != 'all' && $type != 'default') {
            $select->andWhere("v.type= :type")->setParameter('type', $type);
        } else if(!is_null($type) && $type == "default"){

            $select->andWhere("v.type LIKE :type")->setParameter('type', '%default%');
        }

        if (false === is_null($offset))
            $select->setFirstResult($offset);

        if (false === is_null($limit))
            $select->setMaxResults($limit);

        return $select->getQuery()->getResult();
    }

    public function countItems($active = false, $type = null, $id = null)
    {

        $select = $this->createQueryBuilder('v')->select('count(v.id)');


        $select->leftJoin('Application\Entity\User', 'm', Expr\Join::WITH, 'm.id = v.userId');

        if ($id)
            $select->andWhere("v.userId=:uid")->setParameter("uid", (int)$id);
        if (!is_null($type) && $type != 'all' && $type != 'default') {

            $select->andWhere("v.type= :type")->setParameter('type', $type);
        } else if (!is_null($type) && $type == "default") {

            $select->andWhere("v.type LIKE :type")->setParameter('type', '%default%');
        }

        if ($active) {
            $select->andWhere("v.status= :active")->setParameter('active', $active);
        }

        return $select->getQuery()->getSingleScalarResult();
    }

    public function getReviews($video = null)
    {
        if(is_null($video)) {
           return false;
        }

        $select = $this->createQueryBuilder('v')->select('r');


        $select->join('Application\Entity\Reviews', 'r', Expr\Join::WITH, 'v.id = r.resourceId')
               ->where('r.active = :status')
               ->andWhere('r.resourceType = :resource')
               ->andWhere('r.resourceId = :resourceId')
               ->setParameters(array(
                   'status' => Status::ACTIVE,
                   'resource' => 'video', //TODO replace this with constant when constants are added to Application\Entity\Reviews
                   'resourceId' => $video
               ));

        return $select->getQuery()->getResult();
    }

}
