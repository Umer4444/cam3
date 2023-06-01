<?php

namespace Crud\Grid;

use Crud\Traits;

class ScheduledMediaGrid extends BaseGrid\BaseScheduledMediaGrid
{

    use Traits\Filename;

    protected $headers = array(
        'filename' => array(
            'title' => 'Filename',
            'width' => '100',
            'filters' => 'text'
        ),
        'start' => array(
            'title' => 'Start',
            'width' => '100',
            'filters' => 'text'
        ),
        'end' => array(
            'title' => 'End',
            'width' => '100',
            'filters' => 'text'
        ),
        'edit' => array(
            'title' => 'Edit',
            'width' => '100'
        ),
        'delete' => array(
            'title' => 'Delete',
            'width' => '100'
        )
    );

    public function init()
    {

        parent::init();

        $this->getHeader("start")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return $record->getStart()->format('Y-m-d H:i:s');
            }
        ));

        $this->getHeader("end")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return $record->getEnd()->format('Y-m-d H:i:s');
            }
        ));

    }

}

