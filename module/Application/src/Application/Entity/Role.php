<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Entity\Role as PerfectWebRole;

/**
 * Class Role
 * @package Application\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="user_role")
 */
class Role extends PerfectWebRole
{

    const PERFORMER = 'performer';
    const MEMBER = 'member';
    const STUDIO = 'studio';
    const VIP_USER = 'vip_user';
    const ACCOUNT_MANAGER = 'account_manager';
    const MODERATOR = 'moderator';
    const STUDIO_MANAGER = 'studio_manager';

    static function getLoggedInRoles()
    {
        return [
            Role::USER, Role::VIP_USER,
            Role::PERFORMER,
            Role::ACCOUNT_MANAGER, Role::MODERATOR, Role::STUDIO_MANAGER, Role::STUDIO,
            Role::SUPER_ADMIN, Role::ADMIN
        ];
    }

}
