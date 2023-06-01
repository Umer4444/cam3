<?php
/**
 * This file is generated automatically for table "contact_email". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseContactEmailForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('contact_email');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'contact_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ContactId',
            ),
        ));

        $this->add(array(
            'name' => 'tag',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Tag',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'form-control btn-success',
                'style' => 'width: 50%'
            ),
        ), ['priority' => -1000]);
    }


}

