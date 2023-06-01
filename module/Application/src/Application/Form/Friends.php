<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class Friends
 * @package Application\Form
 */
class Friends extends Form
{
    /**
     * @param null $name
     */
    public function __construct()
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

    }
}