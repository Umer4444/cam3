<?php
/**
 * This file is generated automatically for table "model_user_access". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseModelUserAccessGrid extends \ZfTable\AbstractTable
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
        'id_model' => array(
            'title' => 'Id model',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_user' => array(
            'title' => 'Id user',
            'width' => '100',
            'filters' => 'text',
        ),
        'setting' => array(
            'title' => 'Setting',
            'width' => '100',
            'filters' => 'text',
        ),
        'action' => array(
            'title' => 'Action',
            'width' => '100',
            'filters' => 'text',
        ),
        'from' => array(
            'title' => 'From',
            'width' => '100',
            'filters' => 'text',
        ),
        'to' => array(
            'title' => 'To',
            'width' => '100',
            'filters' => 'text',
        ),
        'reason' => array(
            'title' => 'Reason',
            'width' => '100',
            'filters' => 'text',
        ),
        'ip' => array(
            'title' => 'Ip',
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
                return sprintf("<a href=\"/admin/crud/model-user-access/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/model-user-access/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_model');
        if ($value != null) {
            $query->where("id_model like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('setting');
        if ($value != null) {
            $query->where("setting like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('action');
        if ($value != null) {
            $query->where("action like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('from');
        if ($value != null) {
            $query->where("from like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('to');
        if ($value != null) {
            $query->where("to like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reason');
        if ($value != null) {
            $query->where("reason like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ip');
        if ($value != null) {
            $query->where("ip like '%".$value."%' ");
        }
    }


}

