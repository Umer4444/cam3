<?php

namespace Application\Extended\Doctrine\Walkers;

use Doctrine\ORM\Query\AST;
use Doctrine\ORM\Query\SqlWalker;

class Custom extends SqlWalker
{

    private $bdaySortedEntities = [
        \Videos\Entity\Video::class,
        \Images\Entity\Photo::class,
        \Application\Entity\ScheduledMedia::class,
        \Application\Entity\BlogPosts::class,
    ];

    private $pinnedEntities = [
        \Application\Entity\BlogPosts::class,
    ];

    function walkWhenClauseExpression($AST)
    {
        return 'CASE WHEN '.$AST->caseConditionExpression->conditionalTerms[0].' THEN 1 ELSE 0 END';
    }

    public function walkSelectStatement(AST\SelectStatement $AST)
    {

        $components = $this->getQueryComponents();

        $parent = null;
        $parentName = null;
        $foundUserComponent = false;
        $userDql = null;
        $userTableAlias = null;
        foreach ($components as $dqlAlias => $qComp) {
            if ($qComp['parent'] === null && $qComp['nestingLevel'] == 0) {
                $parent = $qComp;
                $parentName = $dqlAlias;
                break;
            }
        }
        foreach ($components as $dqlAlias => $qComp) {
            if (isset($qComp['metadata']) && $qComp['metadata']->getName() == \Application\Entity\User::class) {
                $foundUserComponent = true;
                $userTableAlias = $dqlAlias;
                break;
            }
        }


        // if no ordering, then order by id desc as default
        $foundOrderById = false;
        if (!is_array($AST->orderByClause->orderByItems)) {
            $AST->orderByClause = new \stdClass();
            $AST->orderByClause->orderByItems = [];
        }

        foreach ($AST->orderByClause->orderByItems as $order) {
            if ($order->expression->field == 'id') {
                $foundOrderById = true;
                break;
            }
        }

        if ($foundOrderById) {
            // already ordered by id, so we do nothing
        }

        // check for id column when the id is specified
        elseif (isset($components['id'])) {
            $item = new AST\OrderByItem($components['id']['resultVariable']);
            $item->type = 'DESC';
            $AST->orderByClause->orderByItems[] = $item;
        }

        // or we can select the id from the metadata
        elseif (
            $parent['metadata'] &&
            count($parent['metadata']->getIdentifier()) == 1 && // just ignore composite primary key for now
            !empty($parent['metadata']->getIdentifier()[0])
        ) {

            $field = new AST\PathExpression(
                AST\PathExpression::TYPE_STATE_FIELD,
                $parentName,
                $parent['metadata']->getIdentifier()[0]
            );
            $field->type = AST\PathExpression::TYPE_STATE_FIELD;

            $item = new AST\OrderByItem($field);
            $item->type = 'DESC';

            $AST->orderByClause->orderByItems[] = $item;

        }

        // add order so that it shows first items from the users that have the birthday today
        if (
            (
                in_array($parent['metadata']->name, $this->bdaySortedEntities) ||
                in_array($parent['metadata']->rootEntityName, $this->bdaySortedEntities)
            ) &&
            $parent['metadata']->associationMappings['user']['targetEntity'] == \Application\Entity\User::class
        ) {

            $userJoin = $this->getBirthdayJoin($userTableAlias, $parentName);

            // table is not present in join
            if (!$foundUserComponent) {

                $userTableAlias = 'u';
                $userMetadata = $this->getEntityManager()->getClassMetadata(\Application\Entity\User::class);
                $this->setQueryComponent($userTableAlias, [
                    'metadata' => $userMetadata,
                    'parent' => null, 'relation' => null, 'map' => null, 'nestingLevel' => 0, 'token' => []
                ]);
                $userJoin = $this->getBirthdayJoin($userTableAlias, $parentName);

                $AST->fromClause->identificationVariableDeclarations[0]->joins[] = $userJoin;

            }
            else {
                $AST->fromClause->identificationVariableDeclarations[0]->joins[0]->conditionalExpression->conditionalFactors[] = $userJoin->conditionalExpression;
            }

            $order = new AST\OrderByItem(new AST\WhenClause(new AST\ConditionalExpression(['birthday = CURDATE()']), 1));
            $order->type = 'DESC';

            // push in front so that items from this user are displayed first
            array_unshift($AST->orderByClause->orderByItems, $order);

        }

        // add order so that it shows first items from the items that are pinned
        if (
            in_array($parent['metadata']->name, $this->pinnedEntities) ||
            in_array($parent['metadata']->rootEntityName, $this->pinnedEntities)
        ) {

            $order = new AST\OrderByItem(new AST\WhenClause(new AST\ConditionalExpression(['pinned = 1']), 1));
            $order->type = 'DESC';

            // push in front so that pinned items are displayed first
            array_unshift($AST->orderByClause->orderByItems, $order);

        }

        if (!count($AST->orderByClause->orderByItems)) {
            $AST->orderByClause = null;
        }

        return parent::walkSelectStatement($AST);

    }

    function getBirthdayJoin($userTableAlias, $parentName)
    {

        $userAssociation = new AST\RangeVariableDeclaration(\Application\Entity\User::class, $userTableAlias, false);

        $userPE = new AST\PathExpression(
            AST\PathExpression::TYPE_STATE_FIELD | AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION,
            $userTableAlias, 'id'
        );
        $userPE->type = AST\PathExpression::TYPE_STATE_FIELD;
        $userAE = new AST\ArithmeticExpression();
        $userAE->simpleArithmeticExpression = $userPE;

        $objectPE = new AST\PathExpression(
            AST\PathExpression::TYPE_STATE_FIELD | AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION,
            $parentName, 'user'
        );
        $objectPE->type = AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION;
        $objectAE = new AST\ArithmeticExpression();
        $objectAE->simpleArithmeticExpression = $objectPE;

        $join = new AST\Join(AST\Join::JOIN_TYPE_INNER, $userAssociation);
        $join->conditionalExpression = new AST\ConditionalPrimary();
        $join->conditionalExpression->simpleConditionalExpression = new AST\ComparisonExpression($userAE, '=', $objectAE);

        return $join;

    }

}