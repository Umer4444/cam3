<?php
/**
 * This file is generated automatically for table "user_notifications". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseUserNotificationsGrid extends \ZfTable\AbstractTable
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
        'id_from' => array(
            'title' => 'Id from',
            'width' => '100',
            'filters' => 'text',
        ),
        'type_from' => array(
            'title' => 'Type from',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_to' => array(
            'title' => 'Id to',
            'width' => '100',
            'filters' => 'text',
        ),
        'type_to' => array(
            'title' => 'Type to',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'notification' => array(
            'title' => 'Notification',
            'width' => '100',
            'filters' => 'text',
        ),
        'ip' => array(
            'title' => 'Ip',
            'width' => '100',
            'filters' => 'text',
        ),
        'read' => array(
            'title' => 'Read',
            'width' => '100',
            'filters' => 'text',
        ),
        'date' => array(
            'title' => 'Date',
            'width' => '100',
            'filters' => 'text',
        ),
        'resource' => array(
            'title' => 'Resource',
            'width' => '100',
            'filters' => 'text',
        ),
        'linked_resource' => array(
            'title' => 'Linked resource',
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
                return sprintf("<a href=\"/admin/crud/user-notifications/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/user-notifications/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_from');
        if ($value != null) {
            $query->where("id_from like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type_from');
        if ($value != null) {
            $query->where("type_from like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_to');
        if ($value != null) {
            $query->where("id_to like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type_to');
        if ($value != null) {
            $query->where("type_to like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('notification');
        if ($value != null) {
            $query->where("notification like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ip');
        if ($value != null) {
            $query->where("ip like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('read');
        if ($value != null) {
            $query->where("read like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('date');
        if ($value != null) {
            $query->where("date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('resource');
        if ($value != null) {
            $query->where("resource like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('linked_resource');
        if ($value != null) {
            $query->where("linked_resource like '%".$value."%' ");
        }
    }


}

