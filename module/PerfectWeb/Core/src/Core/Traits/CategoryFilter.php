<?php

namespace PerfectWeb\Core\Traits;

trait CategoryFilter
{

    public function filterByCategory($category)
    {

        if ($category) {

            $this->getEntityManager()
                 ->getFilters()
                 ->enable('category')
                 ->setParameter('category', $category);

        }

    }

}