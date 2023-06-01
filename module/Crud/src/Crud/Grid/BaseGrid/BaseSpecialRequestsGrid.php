<?php
/**
 * This file is generated automatically for table "special_requests". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseSpecialRequestsGrid extends \ZfTable\AbstractTable
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
        'id_model' => array(
            'title' => 'Id model',
            'width' => '100',
            'filters' => 'text',
        ),
        'item' => array(
            'title' => 'Item',
            'width' => '100',
            'filters' => 'text',
        ),
        'offer' => array(
            'title' => 'Offer',
            'width' => '100',
            'filters' => 'text',
        ),
        'deposit' => array(
            'title' => 'Deposit',
            'width' => '100',
            'filters' => 'text',
        ),
        'counter_offer' => array(
            'title' => 'Counter offer',
            'width' => '100',
            'filters' => 'text',
        ),
        'duration' => array(
            'title' => 'Duration',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'terms' => array(
            'title' => 'Terms',
            'width' => '100',
            'filters' => 'text',
        ),
        'want_date' => array(
            'title' => 'Want date',
            'width' => '100',
            'filters' => 'text',
        ),
        'added' => array(
            'title' => 'Added',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_update' => array(
            'title' => 'Last update',
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
                return sprintf("<a href=\"/admin/crud/special-requests/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/special-requests/delete/%s\">Delete</a>", $record->getId());
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

        $value = $this->getParamAdapter()->getValueOfFilter('id_model');
        if ($value != null) {
            $query->where("id_model like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('item');
        if ($value != null) {
            $query->where("item like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('offer');
        if ($value != null) {
            $query->where("offer like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('deposit');
        if ($value != null) {
            $query->where("deposit like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('counter_offer');
        if ($value != null) {
            $query->where("counter_offer like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('duration');
        if ($value != null) {
            $query->where("duration like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('description');
        if ($value != null) {
            $query->where("description like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('terms');
        if ($value != null) {
            $query->where("terms like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('want_date');
        if ($value != null) {
            $query->where("want_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('added');
        if ($value != null) {
            $query->where("added like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_update');
        if ($value != null) {
            $query->where("last_update like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }
    }


}

