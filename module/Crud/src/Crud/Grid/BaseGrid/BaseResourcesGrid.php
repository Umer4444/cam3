<?php
/**
 * This file is generated automatically for table "resources". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseResourcesGrid extends \ZfTable\AbstractTable
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
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'option_config' => array(
            'title' => 'Option config',
            'width' => '100',
            'filters' => 'text',
        ),
        'group_name' => array(
            'title' => 'Group name',
            'width' => '100',
            'filters' => 'text',
        ),
        'label' => array(
            'title' => 'Label',
            'width' => '100',
            'filters' => 'text',
        ),
        'context' => array(
            'title' => 'Context',
            'width' => '100',
            'filters' => 'text',
        ),
        'entity' => array(
            'title' => 'Entity',
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
                return sprintf("<a href=\"/admin/crud/resources/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/resources/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('option_config');
        if ($value != null) {
            $query->where("option_config like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('group_name');
        if ($value != null) {
            $query->where("group_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('label');
        if ($value != null) {
            $query->where("label like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('context');
        if ($value != null) {
            $query->where("context like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('entity');
        if ($value != null) {
            $query->where("entity like '%".$value."%' ");
        }
    }


}

