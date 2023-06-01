<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;
use Nicovogelaar\CrudController\Repository;
use Application\Entity;

/**
 * Class FriendsRepository
 * @package Application\Repository
 */
class FriendsRepository extends EntityRepository
{

    // @todo it is nore required to do another request, just use criteria to search in the collection
    /**
     * @param \Application\Entity\User $user
     * @param \Application\Entity\User $friend
     *
     * @return bool
     */
    public function isFriend(Entity\User $user, Entity\User $friend)
    {

        $isFriend = $this->getEntityManager()
                         ->getRepository('Application\Entity\Friends')
                         ->findOneBy(
                             [
                                 'friend' => $friend,
                                 'user' => $user
                             ]
                         );

        return is_null($isFriend) ? false : true;

    }

}