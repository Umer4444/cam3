<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelRequests
 *
 * @ORM\Table(name="model_requests", indexes={@ORM\Index(name="id_model", columns={"id_model"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class ModelRequests
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_model", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idModel;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="member_camera", type="boolean", nullable=false)
     */
    private $memberCamera = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="viewed", type="integer", nullable=false)
     */
    private $viewed = '0';


}
