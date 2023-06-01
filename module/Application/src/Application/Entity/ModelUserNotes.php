<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelUserNotes
 *
 * @ORM\Table(name="model_user_notes", indexes={@ORM\Index(name="id_model", columns={"id_model"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class ModelUserNotes
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
     * @ORM\Column(name="notes", type="text", length=65535, nullable=false)
     */
    private $notes;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="integer", nullable=false)
     */
    private $added;


}
