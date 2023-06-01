<?php

namespace Images\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class UserImage extends Photo
{

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="covers")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
     *
     * @var \Application\Entity\User
     */
    protected $user;

}