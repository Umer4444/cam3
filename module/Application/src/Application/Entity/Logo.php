<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Uploadable(path="public/uploads/logos", filenameGenerator="SHA1", allowOverwrite=true)
 */
class Logo extends ScheduledMedia
{

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="logos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

}
