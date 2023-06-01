<?php

namespace Images\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BlogImage extends Photo
{

    /**
     * @var \Application\Entity\BlogPosts
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\BlogPosts", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id", nullable=true)
     *
     */
    protected $post;

    /**
     * @return \Application\Entity\BlogPosts
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param \Application\Entity\BlogPosts $post
     */
    public function setPost($post)
    {
        $this->post = $post;
        $this->setEntityReference($post->getId());
    }

}