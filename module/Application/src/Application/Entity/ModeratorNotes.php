<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModeratorNotes
 *
 * @ORM\Table(name="moderator_notes", indexes={@ORM\Index(name="id_moderator", columns={"id_moderator"})})
 * @ORM\Entity
 */
class ModeratorNotes
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
     * @ORM\Column(name="id_moderator", type="integer", nullable=false)
     */
    private $idModerator;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", nullable=false)
     */
    private $userType;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", length=65535, nullable=false)
     */
    private $notes;

    /**
     * @var integer
     *
     * @ORM\Column(name="date", type="integer", nullable=true)
     */
    private $date;


}
