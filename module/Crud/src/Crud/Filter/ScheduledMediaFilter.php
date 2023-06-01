<?php

namespace Crud\Filter;

class ScheduledMediaFilter extends BaseFilter\BaseScheduledMediaFilter
{

    public function __construct()
    {
        parent::__construct();
        $this->getInputFilter()->remove('user_id');
    }

}

