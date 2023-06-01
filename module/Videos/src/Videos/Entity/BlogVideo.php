<?php

namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BlogVideo extends Video
{

    /**
     * @var \Application\Entity\BlogPosts
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\BlogPosts", inversedBy="blogVideo", cascade={"all"})
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $blog;

    /**
     * @return \Application\Entity\BlogPosts
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param $blog
     *
     * @return $this
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
        return $this;
    }

}