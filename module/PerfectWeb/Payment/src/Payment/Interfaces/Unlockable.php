<?php

namespace PerfectWeb\Payment\Interfaces;

interface Unlockable extends Purchasable
{

    function getPassword();

}