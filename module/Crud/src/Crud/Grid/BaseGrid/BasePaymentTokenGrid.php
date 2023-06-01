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


namespace Crud\Grid\BaseGrid;

class BasePaymentTokenGrid extends \ZfTable\AbstractTable
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
        'hash' => array(
            'title' => 'Hash',
            'width' => '100',
            'filters' => 'text',
        ),
        'details' => array(
            'title' => 'Details',
            'width' => '100',
            'filters' => 'text',
        ),
        'after_url' => array(
            'title' => 'After url',
            'width' => '100',
            'filters' => 'text',
        ),
        'target_url' => array(
            'title' => 'Target url',
            'width' => '100',
            'filters' => 'text',
        ),
        'gateway_name' => array(
            'title' => 'Gateway name',
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
                return sprintf("<a href=\"/admin/crud/payment-token/update/%s\">Edit</a>", $record->getId());//hash
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/payment-token/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('hash');
        if ($value != null) {
            $query->where("hash like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('details');
        if ($value != null) {
            $query->where("details like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('after_url');
        if ($value != null) {
            $query->where("after_url like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('target_url');
        if ($value != null) {
            $query->where("target_url like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gateway_name');
        if ($value != null) {
            $query->where("gateway_name like '%".$value."%' ");
        }
    }


}

