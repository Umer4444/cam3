<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelModerator
 *
 * @ORM\Table(name="model_moderator", uniqueConstraints={@ORM\UniqueConstraint(name="model_moderator", columns={"id_model", "id_moderator"})})
 * @ORM\Entity
 */
class ModelModerator
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
     * @ORM\Column(name="id_model", type="integer", nullable=false)
     */
    private $idModel;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_moderator", type="integer", nullable=false)
     */
    private $idModerator;


}
