<?php

namespace PerfectWeb\Core\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @package PerfectWeb\Core\Entity
 *
 * @ORM\MappedSuperclass()
 */
class Role implements HierarchicalRoleInterface
{

    const GUEST = 'guest';
    const USER = 'user';
    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="role_id", unique=true, nullable=true)
     */
    protected $roleId;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", cascade={"all"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set the role id.
     *
     * @param string $roleId
     *
     * @return void
     */
    public function setRoleId($roleId)
    {
        $this->roleId = (string)$roleId;
    }

    /**
     * Get the parent role
     *
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the parent role.
     *
     * @param Role $parent
     *
     * @return $this;
     */
    public function setParent(Role $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    static function getLoggedInRoles()
    {
        return [self::SUPER_ADMIN, self::ADMIN, self::USER];
    }

    static function getAllRoles()
    {
        return (new \ReflectionClass(static::class))->getConstants();
    }

}
