<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="followers")
 * @ORM\Entity()
 */
class Followers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(name="added", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $added;

    /**
     * @ORM\Column(name="new_photo", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $newPhoto;

     /**
     * @ORM\Column(name="new_video", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $newVideo;

    /**
     * @ORM\Column(name="when_online", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $whenOnline;

    /**
     * @ORM\Column(name="blog", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $blog;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
     * @ORM\JoinColumn(name="id_follower", referencedColumnName="id")
     */
    protected $followers;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userFollower",cascade={"all"})
     * @ORM\JoinColumn(name="id_followed", referencedColumnName="id")
     */
    protected $followed;

    /**
     * @ORM\Column(name="pledge", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $pledge;

    /**
     * @return mixed
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @param mixed $added
     */
    public function setAdded($added)
    {
        $this->added = $added;
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param mixed $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNewPhoto()
    {
        return $this->newPhoto;
    }

    /**
     * @param mixed $newPhoto
     */
    public function setNewPhoto($newPhoto)
    {
        $this->newPhoto = $newPhoto;
    }

    /**
     * @return mixed
     */
    public function getNewVideo()
    {
        return $this->newVideo;
    }

    /**
     * @param mixed $newVideo
     */
    public function setNewVideo($newVideo)
    {
        $this->newVideo = $newVideo;
    }

    /**
     * @return mixed
     */
    public function getPledge()
    {
        return $this->pledge;
    }

    /**
     * @param mixed $pledge
     */
    public function setPledge($pledge)
    {
        $this->pledge = $pledge;
    }

    /**
     * @return mixed
     */
    public function getWhenOnline()
    {
        return $this->whenOnline;
    }

    /**
     * @param mixed $whenOnline
     */
    public function setWhenOnline($whenOnline)
    {
        $this->whenOnline = $whenOnline;
    }

    /**
     * @return Users
     */
    public function getFollowed()
    {
        return $this->followed;
    }

    /**
     * @param Users $followed
     */
    public function setFollowed($followed)
    {
        $this->followed = $followed;
    }

    /**
     * @return Users[]
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param Users[] $followers
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
    }

}
