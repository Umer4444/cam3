<?php

namespace Images\Paginator;

use Nicovogelaar\Paginator\Paginator;

class ImagesPaginator extends Paginator
{

    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(18);
    }

}