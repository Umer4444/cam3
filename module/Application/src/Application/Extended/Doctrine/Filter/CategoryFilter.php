<?php

namespace Application\Extended\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class CategoryFilter extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
         return sprintf(
            '(category = %s)',
            $this->getParameter('category')
        );
    }

}