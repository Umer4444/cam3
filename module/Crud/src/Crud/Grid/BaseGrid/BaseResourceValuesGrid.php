<?php
/**
 * This file is generated automatically for table "resource_values". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseResourceValuesGrid extends \ZfTable\AbstractTable
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
        'user_id' => array(
            'title' => 'User id',
            'width' => '100',
            'filters' => 'text',
        ),
        'resource_id' => array(
            'title' => 'Resource id',
            'width' => '100',
            'filters' => 'text',
        ),
        'updated_by' => array(
            'title' => 'Updated by',
            'width' => '100',
            'filters' => 'text',
        ),
        'value' => array(
            'title' => 'Value',
            'width' => '100',
            'filters' => 'text',
        ),
        'referring_to' => array(
            'title' => 'Referring to',
            'width' => '100',
            'filters' => 'text',
        ),
        'updated_on' => array(
            'title' => 'Updated on',
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
                return sprintf("<a href=\"/admin/crud/resource-values/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/resource-values/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_id');
        if ($value != null) {
            $query->where("user_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('resource_id');
        if ($value != null) {
            $query->where("resource_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('updated_by');
        if ($value != null) {
            $query->where("updated_by like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('value');
        if ($value != null) {
            $query->where("value like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('referring_to');
        if ($value != null) {
            $query->where("referring_to like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('updated_on');
        if ($value != null) {
            $query->where("updated_on like '%".$value."%' ");
        }
    }


}

