<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class AddResource
 * @package Application\Form
 */
class AddResource extends Form
{
    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct('application\form');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'key',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'key',
                'placeholder' => 'Setting key',
                'required' => 'required',
            ),
            'options' => array(),
        ));
        $this->add(array(
            'name' => 'label',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'label',
                'placeholder' => 'Label',
                'required' => 'required',
            ),
            'options' => array(),
        ));
        $this->add(array(
            'name' => 'resource',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'resource',
                'placeholder' => 'Resource',
                'required' => 'required',
            ),
            'options' => array(),
        ));

        $this->add(array(
            'name' => 'group',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'group',
                'placeholder' => 'Group',
                'required' => 'required',
            ),
            'options' => array(),
        ));

        $this->add(array(
            'name' => 'entity',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'entity',
                'placeholder' => 'Entity',
            ),
            'options' => array(),
        ));

        $this->add(array(
            'name' => 'add-resource',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'id' => 'add-resource',
                'class' => 'btn btn-primary',
            ),
            'options' => array(
                'label' => 'Save'
            ),
        ));

        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 1800
                )
            )

        ));
    }
}