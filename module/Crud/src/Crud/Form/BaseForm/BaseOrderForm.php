<?php
/**
 * This file is generated automatically for table "order". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseOrderForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('order');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'order_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'OrderId',
            ),
        ));

        $this->add(array(
            'name' => 'billing_address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'BillingAddress',
            ),
        ));

        $this->add(array(
            'name' => 'shipping_address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ShippingAddress',
            ),
        ));

        $this->add(array(
            'name' => 'customer_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'CustomerId',
            ),
        ));

        $this->add(array(
            'name' => 'ref_num',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RefNum',
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
            'name' => 'created_time',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'CreatedTime',
            ),
        ));

        $this->add(array(
            'name' => 'currency_code',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'CurrencyCode',
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

