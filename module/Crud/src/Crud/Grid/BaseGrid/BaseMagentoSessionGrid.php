<?php
/**
 * This file is generated automatically for table "magento_session". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseMagentoSessionGrid extends \ZfTable\AbstractTable
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
        'session_id' => array(
            'title' => 'Session id',
            'width' => '100',
            'filters' => 'text',
        ),
        'session_expires' => array(
            'title' => 'Session expires',
            'width' => '100',
            'filters' => 'text',
        ),
        'session_data' => array(
            'title' => 'Session data',
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
                return sprintf("<a href=\"/admin/crud/magento-session/update/%s\">Edit</a>", $record->getId());//session_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/magento-session/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('session_id');
        if ($value != null) {
            $query->where("session_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('session_expires');
        if ($value != null) {
            $query->where("session_expires like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('session_data');
        if ($value != null) {
            $query->where("session_data like '%".$value."%' ");
        }
    }


}

