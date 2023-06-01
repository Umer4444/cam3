<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * Autoresponders
 *
 * @ORM\Table(name="autoresponders")
 * @ORM\Entity
 */
class Autoresponders
{

    use Traits\User;

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
     * @ORM\Column(name="message", type="string", nullable=false)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="autoresponders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage()
    {

        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {

        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {

        $this->type = $type;
    }

}
