<?php
/**
 * This file is generated automatically for table "chips_packages". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseChipsPackagesGrid extends \ZfTable\AbstractTable
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
        'amount' => array(
            'title' => 'Amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'chips' => array(
            'title' => 'Chips',
            'width' => '100',
            'filters' => 'text',
        ),
        'rate' => array(
            'title' => 'Rate',
            'width' => '100',
            'filters' => 'text',
        ),
        'bonus' => array(
            'title' => 'Bonus',
            'width' => '100',
            'filters' => 'text',
        ),
        'epoch_form' => array(
            'title' => 'Epoch form',
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
                return sprintf("<a href=\"/admin/crud/chips-packages/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/chips-packages/delete/%s\">Delete</a>", $record->getId());
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

        $value = $this->getParamAdapter()->getValueOfFilter('amount');
        if ($value != null) {
            $query->where("amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chips');
        if ($value != null) {
            $query->where("chips like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rate');
        if ($value != null) {
            $query->where("rate like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('bonus');
        if ($value != null) {
            $query->where("bonus like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('epoch_form');
        if ($value != null) {
            $query->where("epoch_form like '%".$value."%' ");
        }
    }


}

