<?php
/**
 * This file is generated automatically for table "rates_limits". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseRatesLimitsGrid extends \ZfTable\AbstractTable
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
        'id_rate' => array(
            'title' => 'Id rate',
            'width' => '100',
            'filters' => 'text',
        ),
        'limit_type' => array(
            'title' => 'Limit type',
            'width' => '100',
            'filters' => 'text',
        ),
        'value' => array(
            'title' => 'Value',
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
                return sprintf("<a href=\"/admin/crud/rates-limits/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/rates-limits/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_rate');
        if ($value != null) {
            $query->where("id_rate like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('limit_type');
        if ($value != null) {
            $query->where("limit_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('value');
        if ($value != null) {
            $query->where("value like '%".$value."%' ");
        }
    }


}

