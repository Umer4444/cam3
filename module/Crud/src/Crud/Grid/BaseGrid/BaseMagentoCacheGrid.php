<?php
/**
 * This file is generated automatically for table "magento_cache". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseMagentoCacheGrid extends \ZfTable\AbstractTable
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
        'data' => array(
            'title' => 'Data',
            'width' => '100',
            'filters' => 'text',
        ),
        'create_time' => array(
            'title' => 'Create time',
            'width' => '100',
            'filters' => 'text',
        ),
        'update_time' => array(
            'title' => 'Update time',
            'width' => '100',
            'filters' => 'text',
        ),
        'expire_time' => array(
            'title' => 'Expire time',
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
                return sprintf("<a href=\"/admin/crud/magento-cache/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/magento-cache/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('data');
        if ($value != null) {
            $query->where("data like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('create_time');
        if ($value != null) {
            $query->where("create_time like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('update_time');
        if ($value != null) {
            $query->where("update_time like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('expire_time');
        if ($value != null) {
            $query->where("expire_time like '%".$value."%' ");
        }
    }


}

