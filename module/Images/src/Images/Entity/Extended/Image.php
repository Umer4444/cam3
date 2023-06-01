<?php

namespace Images\Entity\Extended;

use Application\Mapper\Injector;
use PerfectWeb\Core\Interfaces\Routable;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\Utils\Slug;
use PerfectWeb\Core\View\Helper\Object;

class Image implements Routable
{

    use Traits\Routable;

    function getRoute($type = Object::ROUTE_TYPE_VIEW)
    {
        return 'images/image';
    }

    function getRouteParams()
    {
        return [
            Injector::IMAGE => $this->getId(),
            'slug' => Slug::getSlug($this->getCaption()),
        ];
    }

}
