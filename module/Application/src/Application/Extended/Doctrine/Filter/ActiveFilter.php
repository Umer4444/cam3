<?php

namespace Application\Extended\Doctrine\Filter;

use Application\Entity;
use PerfectWeb\Core\Utils\Status;
use Doctrine\ORM\Mapping\ClassMetaData;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;

class ActiveFilter extends SoftDeleteableFilter
{

    private $ignoredEntities = [
        Entity\Friends::class
    ];

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        if (php_sapi_name() == 'cli' || in_array($targetEntity->getName(), $this->ignoredEntities)) {
            return '';
        }

        foreach ($targetEntity->getColumnNames() as $property => $column) {
            if (!in_array($column, ['active', 'status'])) {
                continue;
            }
            $activeFieldName = $column;
        }

        if (!$activeFieldName) {
            return '';
        }

        $conn = $this->getEntityManager()->getConnection();
        $platform = $conn->getDatabasePlatform();
        $column = $platform->quoteIdentifier($activeFieldName);
        $addCondSql = "{$targetTableAlias}.{$column} = ".Status::ACTIVE;

        return $addCondSql;

    }


}