<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Uploadable(path="public/uploads/chat-backgrounds", filenameGenerator="SHA1", allowOverwrite=true)
 */
class ChatBackground extends ScheduledMedia
{

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="chatBackgrounds")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

}
