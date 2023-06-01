<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotificationsType
 *
 * @ORM\Table(name="user_notifications_type")
 * @ORM\Entity
 */
class UserNotificationsType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=70, nullable=false)
     */
    private $type;


}
