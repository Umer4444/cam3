<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialRequests
 *
 * @ORM\Table(name="special_requests")
 * @ORM\Entity
 */
class SpecialRequests
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

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", nullable=false)
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="offer", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $offer;

    /**
     * @var string
     *
     * @ORM\Column(name="deposit", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $deposit;

    /**
     * @var string
     *
     * @ORM\Column(name="counter_offer", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $counterOffer;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=false)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="terms", type="text", length=65535, nullable=false)
     */
    private $terms;

    /**
     * @var integer
     *
     * @ORM\Column(name="want_date", type="integer", nullable=false)
     */
    private $wantDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="integer", nullable=false)
     */
    private $added;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_update", type="integer", nullable=false)
     */
    private $lastUpdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
