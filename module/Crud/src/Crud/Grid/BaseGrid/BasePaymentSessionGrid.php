<?php
/**
 * This file is generated automatically for table "payment_session". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BasePaymentSessionGrid extends \ZfTable\AbstractTable
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
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'amount' => array(
            'title' => 'Amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'status_data' => array(
            'title' => 'Status data',
            'width' => '100',
            'filters' => 'text',
        ),
        'response_data' => array(
            'title' => 'Response data',
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
        'added' => array(
            'title' => 'Added',
            'width' => '100',
            'filters' => 'text',
        ),
        'encrypt' => array(
            'title' => 'Encrypt',
            'width' => '100',
            'filters' => 'text',
        ),
        'member_id' => array(
            'title' => 'Member id',
            'width' => '100',
            'filters' => 'text',
        ),
        'rate' => array(
            'title' => 'Rate',
            'width' => '100',
            'filters' => 'text',
        ),
        'chips' => array(
            'title' => 'Chips',
            'width' => '100',
            'filters' => 'text',
        ),
        'plan' => array(
            'title' => 'Plan',
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
                return sprintf("<a href=\"/admin/crud/payment-session/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/payment-session/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('amount');
        if ($value != null) {
            $query->where("amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('description');
        if ($value != null) {
            $query->where("description like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status_data');
        if ($value != null) {
            $query->where("status_data like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('response_data');
        if ($value != null) {
            $query->where("response_data like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_type');
        if ($value != null) {
            $query->where("user_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('added');
        if ($value != null) {
            $query->where("added like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('encrypt');
        if ($value != null) {
            $query->where("encrypt like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('member_id');
        if ($value != null) {
            $query->where("member_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rate');
        if ($value != null) {
            $query->where("rate like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chips');
        if ($value != null) {
            $query->where("chips like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('plan');
        if ($value != null) {
            $query->where("plan like '%".$value."%' ");
        }
    }


}

