<?php
namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Categories;
use PerfectWeb\Core\Interfaces\Routable;
use PerfectWeb\Core\View\Helper\Object;
use Application\Mapper\Injector;

/**
 * @ORM\Entity
 */
 class VideoCategory extends Categories implements Routable
 {

    function getRoute($type = Object::ROUTE_TYPE_VIEW)
    {
        return 'vods/list';
    }

    function getRouteParams()
    {
        return [Injector::CATEGORY => $this->getName()];
    }

 }
