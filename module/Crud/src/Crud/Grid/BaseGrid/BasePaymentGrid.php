<?php
/**
 * This file is generated automatically for table "payment". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BasePaymentGrid extends \ZfTable\AbstractTable
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
        'id' => array(
            'title' => 'Id',
            'width' => '100',
            'filters' => 'text',
        ),
        'client_id' => array(
            'title' => 'Client id',
            'width' => '100',
            'filters' => 'text',
        ),
        'number' => array(
            'title' => 'Number',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'client_email' => array(
            'title' => 'Client email',
            'width' => '100',
            'filters' => 'text',
        ),
        'total_amount' => array(
            'title' => 'Total amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'currency_code' => array(
            'title' => 'Currency code',
            'width' => '100',
            'filters' => 'text',
        ),
        'details' => array(
            'title' => 'Details',
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
                return sprintf("<a href=\"/admin/crud/payment/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/payment/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('client_id');
        if ($value != null) {
            $query->where("client_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('number');
        if ($value != null) {
            $query->where("number like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('description');
        if ($value != null) {
            $query->where("description like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('client_email');
        if ($value != null) {
            $query->where("client_email like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('total_amount');
        if ($value != null) {
            $query->where("total_amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('currency_code');
        if ($value != null) {
            $query->where("currency_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('details');
        if ($value != null) {
            $query->where("details like '%".$value."%' ");
        }
    }


}

