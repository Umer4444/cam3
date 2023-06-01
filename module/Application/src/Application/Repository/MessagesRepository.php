<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class BannersRepository
 * @package Application\Repository
 */
class MessagesRepository extends EntityRepository
{
    /**
     * @param $id_user
     * @param int $limit
     * @param int $start
     * @param $type
     * @return \Doctrine\ORM\Query
     */
    public function getUserMessages($id_user, $limit = 30, $start = 0, $type)
    {

        if($type == 'outbox'){

            $type = 'inbox';

            $queryBuilder = $this->createQueryBuilder('m')
                ->select('m')

                ->where("m.senderId = :userId")
                ->andWhere("m.type=:type")
                ->setParameters(array(
                    'type' => $type,
                    'userId' => $id_user
                ))
                ->orderBy("m.sendDate", "DESC")
                ->setFirstResult($start)
                ->setMaxResults($limit);

        } else {


            $queryBuilder = $this->createQueryBuilder('m')
                ->select('m')

                ->where("m.receiverId = :userId")
                ->andWhere("m.type=:type")
                ->setParameters(array(
                    'type' => $type,
                    'userId' => $id_user
                ))
                ->orderBy("m.sendDate", "DESC")
                ->setFirstResult($start)
                ->setMaxResults($limit);


        }

        $result = $queryBuilder->getQuery();






        return $result;
    }

    /**
     * @param $id_user
     * @return \Doctrine\ORM\Query
     */
    public function getUnreadMessages($id_user) {

        $userType = 'user';
        $type = 'inbox';

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->where("m.receiverId = :userId")

            ->andWhere("m.type=:type")
            ->andWhere("m.read=0")
            ->setParameters(array(
                'type' => $type,
                'userId' => $id_user
            ))
            ->orderBy("m.sendDate", "DESC");


        $result = $queryBuilder->getQuery()->getSingleScalarResult();



        return $result;



    }

}