<?php
/**
 * This file is generated automatically for table "model_actions". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseModelActionsForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('model_actions');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Id',
            ),
        ));

        $this->add(array(
            'name' => 'id_model',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'IdModel',
            ),
        ));

        $this->add(array(
            'name' => 'action',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Action',
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Status',
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

