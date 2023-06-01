<?php
/**
 * This file is generated automatically for table "info_modelinfo". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseInfoModelinfoGrid extends \ZfTable\AbstractTable
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
        'info_id' => array(
            'title' => 'Info id',
            'width' => '100',
            'filters' => 'text',
        ),
        'modelinfo_id' => array(
            'title' => 'Modelinfo id',
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
                return sprintf("<a href=\"/admin/crud/info-modelinfo/update/%s\">Edit</a>", $record->getId());//modelinfo_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/info-modelinfo/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('info_id');
        if ($value != null) {
            $query->where("info_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('modelinfo_id');
        if ($value != null) {
            $query->where("modelinfo_id like '%".$value."%' ");
        }
    }


}

