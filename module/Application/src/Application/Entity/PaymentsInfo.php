<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentsInfo
 *
 * @ORM\Table(name="payments_info")
 * @ORM\Entity
 */
class PaymentsInfo
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
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", nullable=false)
     */
    private $userType;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_method", type="smallint", nullable=false)
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text", length=65535, nullable=false)
     */
    private $info;


}
