<?php

namespace Crud\Traits;

trait Category
{

    function onCategory()
    {
        $this->getHeader("category")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return $record->getCategory();
            }
        ));
    }

    function getFormCategory($entity = null)
    {
        return array(
            'name' => 'category_id',
            'type'  => \DoctrineModule\Form\Element\ObjectSelect::class,
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Category',
                'object_manager' => $this->getEntityManager(),
                'target_class' => is_object($entity) ? get_class($entity) : $entity,
                'property' => 'name',
            ),
        );
    }

}