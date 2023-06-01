<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sounds
 *
 * @ORM\Table(name="sounds")
 * @ORM\Entity
 */
class Sounds
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
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;


}
