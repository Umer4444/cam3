<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PledgePerk
 *
 * @ORM\Table(name="pledge_perk")
 * @ORM\Entity
 */
class PledgePerk
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
     * @ORM\Column(name="id_pledge", type="integer", nullable=false)
     */
    private $idPledge;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", nullable=false)
     */
    private $userType;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_limit", type="integer", nullable=false)
     */
    private $userLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="estimated_delivery", type="integer", nullable=false)
     */
    private $estimatedDelivery;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping", type="string", nullable=false)
     */
    private $shipping;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_photo", type="integer", nullable=true)
     */
    private $idPhoto;


}
