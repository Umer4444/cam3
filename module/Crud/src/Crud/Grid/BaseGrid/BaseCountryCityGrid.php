<?php
/**
 * This file is generated automatically for table "country_city". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseCountryCityGrid extends \ZfTable\AbstractTable
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
        'city_code' => array(
            'title' => 'City code',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
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
        'province_code' => array(
            'title' => 'Province code',
            'width' => '100',
            'filters' => 'text',
        ),
        'country_code' => array(
            'title' => 'Country code',
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
                return sprintf("<a href=\"/admin/crud/country-city/update/%s\">Edit</a>", $record->getId());//city_code
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/country-city/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('city_code');
        if ($value != null) {
            $query->where("city_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('geo_lat');
        if ($value != null) {
            $query->where("geo_lat like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('geo_lng');
        if ($value != null) {
            $query->where("geo_lng like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('province_code');
        if ($value != null) {
            $query->where("province_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('country_code');
        if ($value != null) {
            $query->where("country_code like '%".$value."%' ");
        }
    }


}

