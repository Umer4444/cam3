<?php

namespace PerfectWeb\Payment\Interfaces;

interface Purchasable
{

    function getCost();

    function setCost($cost);

    function getUser();

}