<?php

namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @deprecated
 * @ORM\Entity
 */
class ProfileVideo extends Video
{

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="profileVideos")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $profileUser;

    /**
     * @return \Application\Entity\User
     */
    public function getProfileUser()
    {
        return $this->profileUser;
    }

    /**
     * @param \Application\Entity\User $profileUser
     * @return ProfileVideo
     */
    public function setProfileUser($profileUser)
    {
        $this->profileUser = $profileUser;
        return $this;
    }

}