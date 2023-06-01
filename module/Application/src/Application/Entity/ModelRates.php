<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelRates
 *
 * @ORM\Table(name="model_rates", indexes={@ORM\Index(name="id_model", columns={"id_model"}), @ORM\Index(name="id_rate", columns={"id_rate"})})
 * @ORM\Entity
 */
class ModelRates
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
     * @ORM\Column(name="id_rate", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value = '2';

    /**
     * @var integer
     *
     * @ORM\Column(name="special", type="integer", nullable=false)
     */
    private $special = '0';


}
