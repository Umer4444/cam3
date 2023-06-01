<?php

namespace Crud\Traits;

trait Cover
{

    function onCover()
    {
        $this->getHeader("cover")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                $return = sprintf("<a href='%s' target='_blank'><img src=\"%s\" width='50' height='50'/></a>", $record->getCover(), $record->getCover());
                return $return;
            }
        ));
    }

}