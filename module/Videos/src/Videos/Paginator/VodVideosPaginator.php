<?php

namespace Videos\Paginator;

class VodVideosPaginator extends VideosPaginator
{
    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(16);
    }
}