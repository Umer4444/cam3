<?php

namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Payment\Traits;
use PerfectWeb\Payment\Interfaces\Purchasable as Purchasable;

/**
 * @ORM\Entity
 */
class PremiereVideo extends Video implements Purchasable
{
    use Traits\Purchasable;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="premiereVideos")
     */
    protected $user;

    /**
     * @return \Application\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Application\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


}
