<?php
/**
 * This file is generated automatically for table "payment_token". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BasePaymentTokenForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('payment_token');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'hash',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Hash',
            ),
        ));

        $this->add(array(
            'name' => 'details',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Details',
            ),
        ));

        $this->add(array(
            'name' => 'after_url',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'AfterUrl',
            ),
        ));

        $this->add(array(
            'name' => 'target_url',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'TargetUrl',
            ),
        ));

        $this->add(array(
            'name' => 'gateway_name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GatewayName',
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

