<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Payment as PayumPayment;
use PerfectWeb\Core\Traits;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity
 */
class Payment extends PayumPayment
{

    use Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @inheritdoc
     */
    protected $currencyCode = 'USD';

    /**
     * @inheritdoc
     */
    protected $number = 1;

    /**
     * @inheritdoc
     */
    protected $currencyDigitsAfterDecimalPoint = 2;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="payments")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}
