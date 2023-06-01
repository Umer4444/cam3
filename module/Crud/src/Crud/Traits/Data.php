<?php

namespace Crud\Traits;

trait Data
{

    function onData()
    {

        $this->getHeader("data")
             ->getCell()
             ->addDecorator(
                 "callable", array(
                     "callable" => function ($context, $record) {
                         return '<pre>'.print_r($record->getData(), true).'</pre>';
                     }
                 )
             );

    }

}