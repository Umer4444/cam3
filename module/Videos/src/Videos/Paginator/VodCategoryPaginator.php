<?php

namespace Videos\Paginator;

class VodCategoryPaginator extends VideosPaginator
{
    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(20);
    }
}