<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class VideoRepository
 * @package Application\Repository
 */
class RbCommentsRepository extends EntityRepository {

    const active = 1;
    const pending = 0;
    const all = 3;

    /**
     * @param $thread
     * @param null $username
     * @param int $active
     * @return array
     */
    public function getByUsernameForThread($thread, $username = null, $active = self::pending) {

        if(!is_int($active)) {
            return;
        }
        $params = array();
        $params['thread'] = $thread;
        if ($username) {
            $params['author'] = $username;
        }
        if ($active < self::all) {
           $params['visible'] = $active;
        }
        $pending = $this->_em->getRepository('Application\Entity\RbComments')
                ->findBy($params);


        return $pending;

    }

    public function getAllComments(){

        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder
            ->select(array('r.id id', 'r.thread thread', 'r.uri uri', 'r.author author',
                'r.contact contact', 'r.content content', 'r.visible visible', 'r.spam spam',
                'r.published published', 'r.domain performer_id', 's.value domain'
                ))
            ->leftJoin(\PerfectWeb\Core\Entity\ResourceValue::class, 's', 'WITH', 's.userId = r.domain')
            ->leftJoin(\PerfectWeb\Core\Entity\Resource::class, 'ur', 'WITH', "ur.id = s.keyReference")

            ->where("ur.resource = 'domain' OR ur.resource IS NULL")
        ;
        return $result = $queryBuilder->getQuery()->getScalarResult();

    }





}