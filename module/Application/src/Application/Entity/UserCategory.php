<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * @ORM\Entity
 */
class UserCategory extends Categories
{

    use Traits\User;

    /**
    * @var \Application\Entity\User
    *
    * @ORM\ManyToOne(targetEntity="User", inversedBy="category")
    * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $user;

}