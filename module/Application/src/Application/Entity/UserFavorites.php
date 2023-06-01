<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFavorites
 *
 * @ORM\Table(name="user_favorites")
 * @ORM\Entity
 */
class UserFavorites
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
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_model", type="integer", nullable=false)
     */
    private $idModel;


}
