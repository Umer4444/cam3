<?php

namespace PerfectWeb\Core\Traits;

trait User
{

    /**
     * @var \Application\Entity\User
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
     * @param \Application\Entity\User|null $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

}