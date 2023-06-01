<?php

namespace Application\Payment;

trait AmountTrait
{

    protected $amount = 0;

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return AmountTrait
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }


}