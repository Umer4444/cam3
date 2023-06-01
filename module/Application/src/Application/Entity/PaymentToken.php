<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * Payment Token
 *
 * @ORM\Table(name="payment_token")
 * @ORM\Entity
 */
class PaymentToken extends Token
{
}
