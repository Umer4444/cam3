<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TransferWallRepository
 * @package Application\Repository
 */
class TransferWallRepository extends EntityRepository
{
    /**
     * @param \Application\Entity\User $model
     * @return array
     */
    public function getTopTippers(\Application\Entity\User $model)
    {

        // First get the EM handle
        // and call the query builder on it
        $qb = $this->getEntityManager()->createQueryBuilder('c');
        $qb->select(
            array('c.id', 'IDENTITY(c.sender)', 'IDENTITY(c.receiver)', 'c.type', 'SUM(c.amount) AS total')
        )
            ->from('Application\Entity\TransferWall', 'c')
            ->where('c.receiver = :receiver')
            ->andWhere('c.type = :tip')
            ->groupBy('c.sender')
            ->orderBy('total', 'DESC')
            ->setMaxResults(10)
            ->setParameters(array('receiver' => $model, 'tip' => 'tip'));

        $results = $qb->getQuery()->getResult();
        $resultsWithUser = array();
        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');

        foreach($results as $result) {

            $senderId = $result['sender'];
            $userProfile = $userRepo->findOneBy(array('id' => $senderId));

            $result['sender'] = $userProfile;
            $resultsWithUser[] = $result;

        }

        return $resultsWithUser;
    }


}