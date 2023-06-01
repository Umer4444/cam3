<?php
/**
 * This file is generated automatically for table "address". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseAddressGrid extends \ZfTable\AbstractTable
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
        'address_id' => array(
            'title' => 'Address id',
            'width' => '100',
            'filters' => 'text',
        ),
        'city' => array(
            'title' => 'City',
            'width' => '100',
            'filters' => 'text',
        ),
        'country' => array(
            'title' => 'Country',
            'width' => '100',
            'filters' => 'text',
        ),
        'province' => array(
            'title' => 'Province',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'street_address' => array(
            'title' => 'Street address',
            'width' => '100',
            'filters' => 'text',
        ),
        'postal_code' => array(
            'title' => 'Postal code',
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
                return sprintf("<a href=\"/admin/crud/address/update/%s\">Edit</a>", $record->getId());//address_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/address/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('address_id');
        if ($value != null) {
            $query->where("address_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('city');
        if ($value != null) {
            $query->where("city like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('country');
        if ($value != null) {
            $query->where("country like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('province');
        if ($value != null) {
            $query->where("province like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('street_address');
        if ($value != null) {
            $query->where("street_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('postal_code');
        if ($value != null) {
            $query->where("postal_code like '%".$value."%' ");
        }
    }


}

