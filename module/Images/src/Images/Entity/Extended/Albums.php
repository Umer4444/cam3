<?php

namespace Images\Entity\Extended;

use Application\Mapper\Injector;
use PerfectWeb\Core\Interfaces\Routable;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\Utils\Slug;
use PerfectWeb\Core\View\Helper\Object;

class Albums implements Routable
{

    use Traits\Routable;

    function getRoute($type = Object::ROUTE_TYPE_VIEW)
    {
        return 'albums/album';
    }

    function getRouteParams()
    {
        return [
            Injector::ALBUM => $this->getId(),
            Injector::USER => $this->getUser()->getId(),
            'slug' => Slug::getSlug($this->getName()),
        ];
    }

}
