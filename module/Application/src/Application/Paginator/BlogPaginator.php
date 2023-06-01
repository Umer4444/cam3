<?php

namespace Application\Paginator;

use Nicovogelaar\Paginator\Paginator;

class BlogPaginator extends Paginator
{

    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(6);
    }

}