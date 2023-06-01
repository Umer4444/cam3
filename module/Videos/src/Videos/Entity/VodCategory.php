<?php
namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Interfaces\Routable;
use PerfectWeb\Core\Traits;
use Application\Fixtures;
use Videos\Entity;

/**
 * @ORM\Entity
 */
 class VodCategory extends VideoCategory implements Routable
 {}
