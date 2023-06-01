<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PledgeerFundRepository
 * @package Application\Repository
 */
class PledgeFunderRepository extends EntityRepository
{
    //$$this->createQueryBuilder('u')
    /**
     * @param int $modelId
     * @return array
     */
    public function getTopContributers($performerId)
    {

        // First get the EM handle
        // and call the query builder on it
        $qb = $this->getEntityManager()->createQueryBuilder('pf');
        $qb->select(
            array('pf.id', 'pf.pledgeId', 'pf.userId', 'pf.perkId', 'pf.userType', 'SUM(pf.amount) AS total', 'pf.added')
        )
            ->from('Application\Entity\PledgeFunder', 'pf')
            ->join('Application\Entity\Pledge', 'p', 'WITH', 'pf.pledgeId = p.id')
            ->where('p.model = :user')
            ->groupBy('pf.pledgeId')
            ->orderBy('total', 'DESC')
            ->setMaxResults(10)
            ->setParameters(array('user' => $performerId));
        $results = $qb->getQuery()->getResult();




        $resultsWithUser = array();
        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
        foreach($results as $result) {

            $senderId = $result['userId'];
            $userProfile = $userRepo->findOneBy(array('id' => $senderId));

            $result['sender'] = $userProfile;
            $resultsWithUser[] = $result;

        }

        return $resultsWithUser;
    }


}