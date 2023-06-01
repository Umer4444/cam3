<?php

namespace Crud\Traits;

trait Tags
{

    function onTags()
    {
        $this->getHeader("tags")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                $tags = $record->getTags();
                return is_array($tags) ? implode(', ', $tags) : $record->getTags();
            }
        ));
    }

    function addFormTags()
    {
        $specs = array(
            'name' => 'tags',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'data-role' => 'tagsinput'
            ),
            'options' => array(
                'label' => 'Tags',
            ),
        );

        if ($this instanceof \Zend\Form\FormInterface) {
            return $this->add($specs);
        }

        return $specs;

    }

}