<?php
/**
 * This file is generated automatically for table "magento_flag". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseMagentoFlagGrid extends \ZfTable\AbstractTable
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
        'flag_id' => array(
            'title' => 'Flag id',
            'width' => '100',
            'filters' => 'text',
        ),
        'flag_code' => array(
            'title' => 'Flag code',
            'width' => '100',
            'filters' => 'text',
        ),
        'state' => array(
            'title' => 'State',
            'width' => '100',
            'filters' => 'text',
        ),
        'flag_data' => array(
            'title' => 'Flag data',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_update' => array(
            'title' => 'Last update',
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
                return sprintf("<a href=\"/admin/crud/magento-flag/update/%s\">Edit</a>", $record->getId());//flag_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/magento-flag/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('flag_id');
        if ($value != null) {
            $query->where("flag_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('flag_code');
        if ($value != null) {
            $query->where("flag_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('state');
        if ($value != null) {
            $query->where("state like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('flag_data');
        if ($value != null) {
            $query->where("flag_data like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_update');
        if ($value != null) {
            $query->where("last_update like '%".$value."%' ");
        }
    }


}

