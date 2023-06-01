<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

class Logo extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\EntityManager;

    public function __invoke()
    {

        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->getEntityManager()->getRepository(\Application\Entity\Logo::class)->createQueryBuilder('l');
        $qb->where($qb->expr()->lte('l.start', ':now'))
           ->andWhere($qb->expr()->gte('l.end', ':now'))
           ->setMaxResults(1)
           ->setParameters(['now' => (new \DateTime())->format('Y-m-d H:i:s')]);

        $logo = current($qb->getQuery()->getResult()) ?: new \Application\Entity\Logo();

        return $logo->getFilename();

    }

}