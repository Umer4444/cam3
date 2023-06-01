<?php

namespace Application\Widgets\TimeLine\Plugins;

use PerfectWeb\Core\Traits;

class SentTransfers extends AbstractPlugin
{

    use Traits\EntityManager;

    CONST NAME = 'plugins/timeline/sentTransfers';

    /**
     * @inheritdoc
     */
    public function execute()
    {

        if ($this->getUser()) {
            return $this->getUser()->getContributionsHistory()->slice($this->getPage()*$this->getCount(), $this->getCount());
        }

        $offset = $this->getPage();

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