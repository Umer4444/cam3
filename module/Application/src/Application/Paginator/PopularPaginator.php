<?php

namespace Application\Paginator;

use Nicovogelaar\Paginator\Paginator;

class PopularPaginator extends Paginator
{
    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(9);

    }

}