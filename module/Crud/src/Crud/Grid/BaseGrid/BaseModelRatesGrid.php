<?php
/**
 * This file is generated automatically for table "model_rates". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseModelRatesGrid extends \ZfTable\AbstractTable
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
        'id_model' => array(
            'title' => 'Id model',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_rate' => array(
            'title' => 'Id rate',
            'width' => '100',
            'filters' => 'text',
        ),
        'value' => array(
            'title' => 'Value',
            'width' => '100',
            'filters' => 'text',
        ),
        'special' => array(
            'title' => 'Special',
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
                return sprintf("<a href=\"/admin/crud/model-rates/update/%s\">Edit</a>", $record->getId());//id_rate
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/model-rates/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id_model');
        if ($value != null) {
            $query->where("id_model like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_rate');
        if ($value != null) {
            $query->where("id_rate like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('value');
        if ($value != null) {
            $query->where("value like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('special');
        if ($value != null) {
            $query->where("special like '%".$value."%' ");
        }
    }


}

