<?php

namespace Crud\Grid;

class ChatBackgroundGrid extends ScheduledMediaGrid
{

    public function init()
    {

        parent::init();

        $this->getHeader("edit")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/chat-background/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/chat-background/delete/%s\">Delete</a>", $record->getId());
            }
        ));

    }

}

