<?php
/**
 * This file is generated automatically for table "funders". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseFundersGrid extends \ZfTable\AbstractTable
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
        'id_user' => array(
            'title' => 'Id user',
            'width' => '100',
            'filters' => 'text',
        ),
        'reference_id' => array(
            'title' => 'Reference id',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_perk' => array(
            'title' => 'Id perk',
            'width' => '100',
            'filters' => 'text',
        ),
        'amount' => array(
            'title' => 'Amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'date' => array(
            'title' => 'Date',
            'width' => '100',
            'filters' => 'text',
        ),
        'anonymous' => array(
            'title' => 'Anonymous',
            'width' => '100',
            'filters' => 'text',
        ),
        'entity' => array(
            'title' => 'Entity',
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
                return sprintf("<a href=\"/admin/crud/funders/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/funders/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reference_id');
        if ($value != null) {
            $query->where("reference_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_perk');
        if ($value != null) {
            $query->where("id_perk like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('amount');
        if ($value != null) {
            $query->where("amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('date');
        if ($value != null) {
            $query->where("date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('anonymous');
        if ($value != null) {
            $query->where("anonymous like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('entity');
        if ($value != null) {
            $query->where("entity like '%".$value."%' ");
        }
    }


}

