<?php

namespace Crud\Traits;

trait ObjectClass
{

    static $objectClassMapping = array(
        \Videos\Entity\Video::class => 'Video',
        \Videos\Entity\VodVideo::class => 'Vod',
        \Videos\Entity\PremiereVideo::class => 'Premiere',
        \Images\Entity\Albums::class => 'Photo Albums',
        \Images\Entity\Photo::class => 'Photo',
        \Videos\Entity\ShowVideo::class => 'Show',
        \Videos\Entity\ProfileVideo::class => 'Profile',
        \Videos\Entity\PledgeVideo::class => 'Pledge',
    );

    function onObjectClas()
    {
        $self = $this;
        $this->getHeader("object_class")
             ->getCell()
             ->addDecorator(
                 "callable", array(
                     "callable" => function ($context, $record) use ($self) {
                         return $self->objectToName($record->getObjectClass());
                     }
                 )
             );

    }

    static function objectToName($className)
    {

        if (isset(self::$objectClassMapping[$className])) {
            return self::$objectClassMapping[$className];
        }

        $parts = explode('\\', $className);
        return $parts[count($parts) - 1];

    }

}