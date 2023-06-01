<?php

namespace Videos\Paginator;

use Nicovogelaar\Paginator\Paginator;

class VideosPaginator extends Paginator
{
    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(16);

    }

}