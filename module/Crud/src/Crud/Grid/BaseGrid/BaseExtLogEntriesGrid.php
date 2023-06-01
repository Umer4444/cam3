<?php
/**
 * This file is generated automatically for table "ext_log_entries". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseExtLogEntriesGrid extends \ZfTable\AbstractTable
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
        'action' => array(
            'title' => 'Action',
            'width' => '100',
            'filters' => 'text',
        ),
        'logged_at' => array(
            'title' => 'Logged at',
            'width' => '100',
            'filters' => 'text',
        ),
        'object_id' => array(
            'title' => 'Object id',
            'width' => '100',
            'filters' => 'text',
        ),
        'object_class' => array(
            'title' => 'Object class',
            'width' => '100',
            'filters' => 'text',
        ),
        'version' => array(
            'title' => 'Version',
            'width' => '100',
            'filters' => 'text',
        ),
        'data' => array(
            'title' => 'Data',
            'width' => '100',
            'filters' => 'text',
        ),
        'username' => array(
            'title' => 'Username',
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
                return sprintf("<a href=\"/admin/crud/ext-log-entries/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/ext-log-entries/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('action');
        if ($value != null) {
            $query->where("action like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('logged_at');
        if ($value != null) {
            $query->where("logged_at like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('object_id');
        if ($value != null) {
            $query->where("object_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('object_class');
        if ($value != null) {
            $query->where("object_class like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('version');
        if ($value != null) {
            $query->where("version like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('data');
        if ($value != null) {
            $query->where("data like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('username');
        if ($value != null) {
            $query->where("username like '%".$value."%' ");
        }
    }


}

