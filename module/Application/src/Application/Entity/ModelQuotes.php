<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelQuotes
 *
 * @ORM\Table(name="model_quotes", indexes={@ORM\Index(name="id_model", columns={"id_model"})})
 * @ORM\Entity
 */
class ModelQuotes
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
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=false)
     */
    private $text;


}
