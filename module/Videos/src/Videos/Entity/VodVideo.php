<?php

namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Payment;
use PerfectWeb\Core\Interfaces\Routable;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\View\Helper\Object;
use PerfectWeb\Core\Utils\Slug;
use Application\Mapper\Injector;


/**
 * @ORM\Entity
 */
class VodVideo extends Video implements Payment\Interfaces\Purchasable, Routable
{


    use Payment\Traits\Purchasable;
    use Traits\Routable;

    /**
     * @var \Videos\Entity\VodCategory
     *
     * @ORM\ManyToOne(targetEntity="Videos\Entity\VodCategory", inversedBy="videos")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;

    function getRoute($type = Object::ROUTE_TYPE_VIEW)
    {
        return 'vods/list';
    }

    function getRouteParams()
    {
        return
            [
            Injector::VIDEO => $this->getId(),
            'slug' => Slug::getSlug($this->getTitle())
            ];
    }

}
