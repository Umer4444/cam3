<?php

namespace Application\Traits;

trait EndDate
{

    protected $endDate;

    function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param $endDate
     *
     * @return $this
     */
    function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

}