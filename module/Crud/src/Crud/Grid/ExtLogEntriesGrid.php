<?php

namespace Crud\Grid;

use Crud\Traits;

class ExtLogEntriesGrid extends BaseGrid\BaseExtLogEntriesGrid
{

    use Traits\Date;
    use Traits\Data;
    use Traits\ObjectClass;

    protected $headers = array(
        'action' => array(
            'title' => 'Action',
            'width' => '100',
            'filters' => 'text'
        ),
        'object_class' => array(
            'title' => 'Object class',
            'width' => '100',
            'filters' => 'text',
        ),
        'logged_at' => array(
            'title' => 'Logged at',
            'width' => '100',
            'filters' => 'text'
        ),
        'data' => array(
            'title' => 'Data',
            'width' => '100',
            'filters' => 'text'
        ),
        'username' => array(
            'title' => 'Username',
            'width' => '100',
            'filters' => 'text'
        )
    );

    public function init()
    {
        foreach (get_class_methods($this) as $method) { if (substr($method, 0, 2) == "on") {$this->$method();}}
    }

}

