<?php
/**
 * This file is generated automatically for table "payments". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BasePaymentsGrid extends \ZfTable\AbstractTable
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
        'added' => array(
            'title' => 'Added',
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
        'id_order' => array(
            'title' => 'Id order',
            'width' => '100',
            'filters' => 'text',
        ),
        'start_date' => array(
            'title' => 'Start date',
            'width' => '100',
            'filters' => 'text',
        ),
        'end_date' => array(
            'title' => 'End date',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_pachet' => array(
            'title' => 'Id pachet',
            'width' => '100',
            'filters' => 'text',
        ),
        'amount' => array(
            'title' => 'Amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'currency' => array(
            'title' => 'Currency',
            'width' => '100',
            'filters' => 'text',
        ),
        'chips' => array(
            'title' => 'Chips',
            'width' => '100',
            'filters' => 'text',
        ),
        'payment_method' => array(
            'title' => 'Payment method',
            'width' => '100',
            'filters' => 'text',
        ),
        'payment_type' => array(
            'title' => 'Payment type',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
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
                return sprintf("<a href=\"/admin/crud/payments/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/payments/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('added');
        if ($value != null) {
            $query->where("added like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_type');
        if ($value != null) {
            $query->where("user_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_order');
        if ($value != null) {
            $query->where("id_order like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('start_date');
        if ($value != null) {
            $query->where("start_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('end_date');
        if ($value != null) {
            $query->where("end_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_pachet');
        if ($value != null) {
            $query->where("id_pachet like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('amount');
        if ($value != null) {
            $query->where("amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('currency');
        if ($value != null) {
            $query->where("currency like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chips');
        if ($value != null) {
            $query->where("chips like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('payment_method');
        if ($value != null) {
            $query->where("payment_method like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('payment_type');
        if ($value != null) {
            $query->where("payment_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }
    }


}

