<?php

namespace Crud\Form;

class ScheduledMediaForm extends BaseForm\BaseLogoForm
{

    public function __construct()
    {

        parent::__construct();

        $this->remove('id');
        $this->get('end')->setAttribute('data-type', 'datetime');
        $this->get('start')->setAttribute('data-type', 'datetime');

        $this->remove('user');
        $this->remove('filename');

        $this->add(array(
            'name' => 'user',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'filename',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Filename',
            ),
        ));

    }

}