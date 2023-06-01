<?php

namespace Application\Traits;

trait StartDate
{

    protected $startDate;

    function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param $startDate
     *
     * @return $this
     */
    function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

}