<?php

namespace PerfectWeb\Core\Traits;

use PerfectWeb\Core\Traits;

trait ResourceGroup
{
    use Traits\EntityManager;

    public function getGroup($group)
    {
        if ($group) {

            $social = $this->getEntityManager()
                ->getRepository('PerfectWeb\Core\Entity\Resource')
                ->findBy(['group' => $group]);

            foreach ($social as $resource) {
                $socialResources[] = $resource->getId();
            }
            return $socialResources;
        }

    }

}