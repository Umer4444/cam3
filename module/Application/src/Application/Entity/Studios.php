<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Studios
 *
 * @ORM\Table(name="studios")
 * @ORM\Entity
 */
class Studios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var mixed
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     */
    protected $name;

    /**
     * @var mixed
     *
     * @ORM\Column(name="address", type="string", nullable=false)
     *
     */
    protected $address;
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\User", mappedBy="studios")
     **/
    protected $users;
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\User", mappedBy="managers")
     **/
    protected $managers;

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }


    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}