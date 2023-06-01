<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotificationsMail
 *
 * @ORM\Table(name="user_notifications_mail")
 * @ORM\Entity
 */
class UserNotificationsMail
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
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", length=25, nullable=false)
     */
    private $userType;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_type", type="string", length=70, nullable=false)
     */
    private $notificationType;


}
