<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentSession
 *
 * @ORM\Table(name="payment_session")
 * @ORM\Entity
 */
class PaymentSession
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=64, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="status_data", type="text", length=65535, nullable=false)
     */
    private $statusData;

    /**
     * @var string
     *
     * @ORM\Column(name="response_data", type="text", length=65535, nullable=false)
     */
    private $responseData;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", nullable=false)
     */
    private $userType;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="integer", nullable=false)
     */
    private $added;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypt", type="string", length=50, nullable=true)
     */
    private $encrypt;

    /**
     * @var string
     *
     * @ORM\Column(name="member_id", type="string", length=30, nullable=true)
     */
    private $memberId;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $rate = '0.000';

    /**
     * @var integer
     *
     * @ORM\Column(name="chips", type="integer", nullable=false)
     */
    private $chips = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="plan", type="integer", nullable=false)
     */
    private $plan;


}
