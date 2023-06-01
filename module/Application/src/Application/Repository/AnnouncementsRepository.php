<?php

namespace Application\Repository;

use Application\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class AnnouncementsRepository
 * @package Application\Repository
 */
class AnnouncementsRepository extends EntityRepository
{

    public function getAnnouncements(User $user)
    {

        return $this->createQueryBuilder('a')
                    ->where('a.section IN (:sections)')
                    ->setMaxResults(5)
                    ->setParameters([
                       'sections' => ['ALL', $user->getRole()]
                    ])
                    ->getQuery()
                    ->getResult();

    }

}