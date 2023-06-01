<?php

namespace Crud\Traits;

trait Date
{

    function onUploadedOn()
    {
        if ($this->getHeaders()["uploaded_on"]) {
            $this->getHeader("uploaded_on")
                 ->getCell()
                 ->addDecorator(
                     "callable", array(
                         "callable" => function ($context, $record) {
                             return $record->getUploadedOn()
                                           ->format('Y-m-d H:i');
                         }
                     )
                 );
        }
    }

    function onLoggedAt()
    {
        if ($this->getHeaders()["logged_at"]) {
            $this->getHeader("logged_at")
                 ->getCell()
                 ->addDecorator(
                     "callable", array(
                         "callable" => function ($context, $record) {
                             return $record->getLoggedAt()
                                           ->format('Y-m-d H:i');
                         }
                     )
                 );
        }
    }

    function onStart()
    {
        if ($this->getHeaders()["start"]) {
            $this->getHeader("start")
                 ->getCell()
                 ->addDecorator(
                     "callable", array(
                         "callable" => function ($context, $record) {
                             return $record->getStart()
                                           ->format('Y-m-d H:i');
                         }
                     )
                 );
        }
    }

    function onEnd()
    {
        if ($this->getHeaders()["end"]) {
            $this->getHeader("end")
                 ->getCell()
                 ->addDecorator(
                     "callable", array(
                         "callable" => function ($context, $record) {
                             return $record->getEnd()
                                           ->format('Y-m-d H:i');
                         }
                     )
                 );
        }
    }

    function onPublishDate()
    {
        if ($this->getHeaders()["publish_date"]) {
            $this->getHeader("publish_date")
                 ->getCell()
                 ->addDecorator(
                     "callable", array(
                         "callable" => function ($context, $record) {
                             return $record->getPublishDate()
                                           ->format('Y-m-d H:i');
                         }
                     )
                 );
        }
    }

}