<?php

namespace PerfectWeb\Payment\Traits;

trait Purchasable
{

    protected $cost = 0;

    function getCost()
    {
        return $this->cost;
    }

    /**
     * @param $cost
     *
     * @return $this
     */
    function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

}