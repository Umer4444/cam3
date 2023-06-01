<?php

namespace Application\Widgets\TimeLine\Plugins;

use PerfectWeb\Core\Traits;

class Transfers extends AbstractPlugin
{
    use Traits\EntityManager;

    CONST NAME = 'plugins/timeline/transfers';

    /**
     * @inheritdoc
     */
    public function execute()
    {

        if ($this->getUser()) {
            return $this->getUser()->getContributorsHistory()->slice($this->getPage()*$this->getCount(), $this->getCount());
        }

        return $this->getEntityManager()
            ->getRepository(\Application\Entity\TransferWall::class)
            ->findBy(
                array(),
                array('date' => 'DESC'),
                $this->getCount(),
                $this->getPage()*$this->getCount()
            );

    }

}