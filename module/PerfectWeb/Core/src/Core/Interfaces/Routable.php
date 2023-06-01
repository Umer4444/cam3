<?php

namespace PerfectWeb\Core\Interfaces;

interface Routable
{

    function getRoute($type);

    function getRouteParams();

}