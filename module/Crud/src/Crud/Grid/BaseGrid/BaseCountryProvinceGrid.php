<?php
/**
 * This file is generated automatically for table "country_province". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseCountryProvinceGrid extends \ZfTable\AbstractTable
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
        'country_province_code' => array(
            'title' => 'Country province code',
            'width' => '100',
            'filters' => 'text',
        ),
        'country_name' => array(
            'title' => 'Country name',
            'width' => '100',
            'filters' => 'text',
        ),
        'province_name' => array(
            'title' => 'Province name',
            'width' => '100',
            'filters' => 'text',
        ),
        'province_alternate_names' => array(
            'title' => 'Province alternate names',
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
                return sprintf("<a href=\"/admin/crud/country-province/update/%s\">Edit</a>", $record->getId());//country_province_code
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/country-province/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('country_province_code');
        if ($value != null) {
            $query->where("country_province_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('country_name');
        if ($value != null) {
            $query->where("country_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('province_name');
        if ($value != null) {
            $query->where("province_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('province_alternate_names');
        if ($value != null) {
            $query->where("province_alternate_names like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('country_code');
        if ($value != null) {
            $query->where("country_code like '%".$value."%' ");
        }
    }


}

