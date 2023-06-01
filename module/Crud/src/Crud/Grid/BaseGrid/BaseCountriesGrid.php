<?php
/**
 * This file is generated automatically for table "countries". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseCountriesGrid extends \ZfTable\AbstractTable
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
        'code' => array(
            'title' => 'Code',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'in_location' => array(
            'title' => 'In location',
            'width' => '100',
            'filters' => 'text',
        ),
        'geo_lat' => array(
            'title' => 'Geo lat',
            'width' => '100',
            'filters' => 'text',
        ),
        'geo_lng' => array(
            'title' => 'Geo lng',
            'width' => '100',
            'filters' => 'text',
        ),
        'db_id' => array(
            'title' => 'Db id',
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
                return sprintf("<a href=\"/admin/crud/countries/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/countries/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('code');
        if ($value != null) {
            $query->where("code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('in_location');
        if ($value != null) {
            $query->where("in_location like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('geo_lat');
        if ($value != null) {
            $query->where("geo_lat like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('geo_lng');
        if ($value != null) {
            $query->where("geo_lng like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('db_id');
        if ($value != null) {
            $query->where("db_id like '%".$value."%' ");
        }
    }


}

