<?php

namespace Videos\Paginator;

use Videos\Paginator\VideosPaginator;

class PremiereVideoPaginator extends VideosPaginator
{
    public function init()
    {
        $this->getPaginator()->setItemCountPerPage(16);

    }

}