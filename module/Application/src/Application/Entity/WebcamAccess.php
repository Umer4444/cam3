<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebcamAccess
 *
 * @ORM\Table(name="webcam_access")
 * @ORM\Entity
 */
class WebcamAccess
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
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type;


}
