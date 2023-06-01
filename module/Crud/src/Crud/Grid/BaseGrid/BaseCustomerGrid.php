<?php
/**
 * This file is generated automatically for table "customer". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseCustomerGrid extends \ZfTable\AbstractTable
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
        'idcustomer' => array(
            'title' => 'Idcustomer',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'surname' => array(
            'title' => 'Surname',
            'width' => '100',
            'filters' => 'text',
        ),
        'street' => array(
            'title' => 'Street',
            'width' => '100',
            'filters' => 'text',
        ),
        'city' => array(
            'title' => 'City',
            'width' => '100',
            'filters' => 'text',
        ),
        'active' => array(
            'title' => 'Active',
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
                return sprintf("<a href=\"/admin/crud/customer/update/%s\">Edit</a>", $record->getId());//idcustomer
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/customer/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('idcustomer');
        if ($value != null) {
            $query->where("idcustomer like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('surname');
        if ($value != null) {
            $query->where("surname like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('street');
        if ($value != null) {
            $query->where("street like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('city');
        if ($value != null) {
            $query->where("city like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('active');
        if ($value != null) {
            $query->where("active like '%".$value."%' ");
        }
    }


}

