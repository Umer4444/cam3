<?php

namespace Crud\Traits;

trait Filename
{

    function onFilename()
    {
        $this->getHeader("filename")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){

                $ext = substr($record->getFilename(), -3);

                if (in_array($ext, ['mp4', 'flv'])) {

                    $return = sprintf(
                        "<a href='%s' target='_blank'><img src=\"%s\" width='50' height='50'/></a>",
                        $record->getFilename(false),
                        $record->getCover()
                    );
                }
                else {
                    $return = sprintf(
                        "<a href='%s' target='_blank'><img src=\"%s\" width='50' height='50'/></a>",
                        $record->getFilename(false), $record->getFilename(false)
                    );
                }

                return $return;
            }
        ));
    }

}