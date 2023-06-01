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


namespace Crud\Grid\BaseGrid;

class BaseOrderGrid extends \ZfTable\AbstractTable
{

    protected $config = array(
        'name' => '',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => false,
    );

    protected $headers = array(
        'order_id' => array(
            'title' => 'Order id',
            'width' => '100',
            'filters' => 'text',
        ),
        'billing_address' => array(
            'title' => 'Billing address',
            'width' => '100',
            'filters' => 'text',
        ),
        'shipping_address' => array(
            'title' => 'Shipping address',
            'width' => '100',
            'filters' => 'text',
        ),
        'customer_id' => array(
            'title' => 'Customer id',
            'width' => '100',
            'filters' => 'text',
        ),
        'ref_num' => array(
            'title' => 'Ref num',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'created_time' => array(
            'title' => 'Created time',
            'width' => '100',
            'filters' => 'text',
        ),
        'currency_code' => array(
            'title' => 'Currency code',
            'width' => '100',
            'filters' => 'text',
        ),
        'edit' => array(
            'title' => 'Edit',
            'width' => '100',
        ),
        'delete' => array(
            'title' => 'Delete',
            'width' => '100',
        ),
    );

    public function init()
    {
        foreach (get_class_methods($this) as $method) { if (substr($method, 0, 2) == "on") {$this->$method();}} 
        $this->getHeader("edit")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/order/update/%s\">Edit</a>", $record->getId());//order_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/order/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('order_id');
        if ($value != null) {
            $query->where("order_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('billing_address');
        if ($value != null) {
            $query->where("billing_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('shipping_address');
        if ($value != null) {
            $query->where("shipping_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('customer_id');
        if ($value != null) {
            $query->where("customer_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ref_num');
        if ($value != null) {
            $query->where("ref_num like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('created_time');
        if ($value != null) {
            $query->where("created_time like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('currency_code');
        if ($value != null) {
            $query->where("currency_code like '%".$value."%' ");
        }
    }


}

