<?php

namespace Application\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class AddReview
 * @package Application\Form
 */
class AddReview extends Form
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct('application\form');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'review',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
            ),
            'options' => array(),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'Submit',
                'value' => 'Save',
                'class' => 'btn btn-success btn'

            ),
        ));
    }
}