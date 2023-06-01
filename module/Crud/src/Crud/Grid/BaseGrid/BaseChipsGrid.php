<?php
/**
 * This file is generated automatically for table "chips". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseChipsGrid extends \ZfTable\AbstractTable
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
        'id_sender' => array(
            'title' => 'Id sender',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_receiver' => array(
            'title' => 'Id receiver',
            'width' => '100',
            'filters' => 'text',
        ),
        'receiver_type' => array(
            'title' => 'Receiver type',
            'width' => '100',
            'filters' => 'text',
        ),
        'data' => array(
            'title' => 'Data',
            'width' => '100',
            'filters' => 'text',
        ),
        'amount' => array(
            'title' => 'Amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
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
                return sprintf("<a href=\"/admin/crud/chips/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/chips/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_sender');
        if ($value != null) {
            $query->where("id_sender like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_receiver');
        if ($value != null) {
            $query->where("id_receiver like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('receiver_type');
        if ($value != null) {
            $query->where("receiver_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('data');
        if ($value != null) {
            $query->where("data like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('amount');
        if ($value != null) {
            $query->where("amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }
    }


}

