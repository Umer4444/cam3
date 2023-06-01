<?php

namespace PerfectWeb\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * UserAccess
 *
 * @ORM\Table(name="user_access")
 * @ORM\Entity
 */
class UserAccess
{

    use Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="resourceAccess")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \PerfectWeb\Core\Entity\Resource
     *
     * @ORM\ManyToOne(targetEntity="PerfectWeb\Core\Entity\Resource")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     */
    protected $resource;

    /**
     * @var string
     *
     * @ORM\Column(name="permission", type="string", nullable=false)
     */
    protected $permission;

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
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param $permission
     *
     * @return $this
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
        return $this;
    }

    public function __toString()
    {
        return $this->getResource();
    }

    /**
     * @return \PerfectWeb\Core\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param \PerfectWeb\Core\Entity\Resource $resource
     *
     * @return $this
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }

}