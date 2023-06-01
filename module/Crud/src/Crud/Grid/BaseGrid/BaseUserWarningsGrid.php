<?php
/**
 * This file is generated automatically for table "user_warnings". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseUserWarningsGrid extends \ZfTable\AbstractTable
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
        'user' => array(
            'title' => 'User',
            'width' => '100',
            'filters' => 'text',
        ),
        'userBy' => array(
            'title' => 'UserBy',
            'width' => '100',
            'filters' => 'text',
        ),
        'date' => array(
            'title' => 'Date',
            'width' => '100',
            'filters' => 'text',
        ),
        'reason' => array(
            'title' => 'Reason',
            'width' => '100',
            'filters' => 'text',
        ),
        'weight' => array(
            'title' => 'Weight',
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
                return sprintf("<a href=\"/admin/crud/user-warnings/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/user-warnings/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user');
        if ($value != null) {
            $query->where("user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('userBy');
        if ($value != null) {
            $query->where("userBy like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('date');
        if ($value != null) {
            $query->where("date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reason');
        if ($value != null) {
            $query->where("reason like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('weight');
        if ($value != null) {
            $query->where("weight like '%".$value."%' ");
        }
    }


}

