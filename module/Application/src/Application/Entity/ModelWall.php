<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelWall
 *
 * @ORM\Table(name="model_wall", indexes={@ORM\Index(name="OID", columns={"id_owner"}), @ORM\Index(name="UID", columns={"id_user"})})
 * @ORM\Entity
 */
class ModelWall
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_wall", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idWall;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_owner", type="integer", nullable=false)
     */
    private $idOwner;

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
     * @ORM\Column(name="message", type="text", length=65535, nullable=false)
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="integer", nullable=false)
     */
    private $added;


}
