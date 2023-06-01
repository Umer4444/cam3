<?php
/**
 * This file is generated automatically for table "payments_info". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BasePaymentsInfoGrid extends \ZfTable\AbstractTable
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
        'id_user' => array(
            'title' => 'Id user',
            'width' => '100',
            'filters' => 'text',
        ),
        'user_type' => array(
            'title' => 'User type',
            'width' => '100',
            'filters' => 'text',
        ),
        'payment_method' => array(
            'title' => 'Payment method',
            'width' => '100',
            'filters' => 'text',
        ),
        'info' => array(
            'title' => 'Info',
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
                return sprintf("<a href=\"/admin/crud/payments-info/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/payments-info/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_type');
        if ($value != null) {
            $query->where("user_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('payment_method');
        if ($value != null) {
            $query->where("payment_method like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('info');
        if ($value != null) {
            $query->where("info like '%".$value."%' ");
        }
    }


}

