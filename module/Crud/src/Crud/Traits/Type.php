<?php

namespace Crud\Traits;

trait Type
{

    use ObjectClass;

    /**
     * @param null $filterType
     *
     * @return array
     */
    function getFormType($filterType = null)
    {

        $values = self::$objectClassMapping;

        if ($filterType) {
            foreach ($values as $class => $name) {
                if (!($class == $filterType || is_subclass_of($class, $filterType))) {
                    unset($values[$class]);
                }
            }
        }

        return array(
            'name' => 'type',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Type',
                'value_options' => $values,
            ),
        );
    }

}