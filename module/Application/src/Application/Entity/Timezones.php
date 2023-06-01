<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timezones
 *
 * @ORM\Table(name="timezones",indexes={@ORM\Index(name="timezone_idx", columns={"id"})})
 * @ORM\Entity
 */
class Timezones
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", length=11, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="GMT", type="string", length=6, nullable=false)
     *
     * @var mixed
     */
    protected $gmt;

    /**
     * @ORM\Column(name="name", type="string", length=120, nullable=false)
     *
     * @var mixed
     */
    protected $name;

    public function getId()
    {
        return $this->id;
    }

    public function getGmt()
    {
        return $this->gmt;
    }

    public function setGmt($gmt)
    {
        $this->gmt = $gmt;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}