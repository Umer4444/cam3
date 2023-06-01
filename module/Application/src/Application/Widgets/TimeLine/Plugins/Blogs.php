<?php

namespace Application\Widgets\TimeLine\Plugins;

use PerfectWeb\Core\Traits;

class Blogs extends AbstractPlugin
{
    use Traits\EntityManager;

    CONST NAME = 'plugins/timeline/blogs';

    /**
     * @inheritdoc
     */
    public function execute()
    {

        if ($this->getUser()) {

            return $this->getUser()->getBlogPosts()->slice($this->getPage()*$this->getCount(), $this->getCount());
        }

        return $this->getEntityManager()
            ->getRepository(\Application\Entity\BlogPosts::class)
            ->findBy(
                array(),
                array('postedOn' => 'DESC'),
                $this->getCount(),
                $this->getPage()*$this->getCount()
            );

    }

}