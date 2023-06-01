<?php
/**
 * This file is generated automatically for table "webchat_users". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseWebchatUsersGrid extends \ZfTable\AbstractTable
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
        'gravatar' => array(
            'title' => 'Gravatar',
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
        'chat_type' => array(
            'title' => 'Chat type',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_activity' => array(
            'title' => 'Last activity',
            'width' => '100',
            'filters' => 'text',
        ),
        'loggedin' => array(
            'title' => 'Loggedin',
            'width' => '100',
            'filters' => 'text',
        ),
        'broadcast_mode' => array(
            'title' => 'Broadcast mode',
            'width' => '100',
            'filters' => 'text',
        ),
        'quality' => array(
            'title' => 'Quality',
            'width' => '100',
            'filters' => 'text',
        ),
        'chat_font' => array(
            'title' => 'Chat font',
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
                return sprintf("<a href=\"/admin/crud/webchat-users/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/webchat-users/delete/%s\">Delete</a>", $record->getId());
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

        $value = $this->getParamAdapter()->getValueOfFilter('gravatar');
        if ($value != null) {
            $query->where("gravatar like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_model');
        if ($value != null) {
            $query->where("id_model like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chat_type');
        if ($value != null) {
            $query->where("chat_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_activity');
        if ($value != null) {
            $query->where("last_activity like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('loggedin');
        if ($value != null) {
            $query->where("loggedin like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('broadcast_mode');
        if ($value != null) {
            $query->where("broadcast_mode like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('quality');
        if ($value != null) {
            $query->where("quality like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chat_font');
        if ($value != null) {
            $query->where("chat_font like '%".$value."%' ");
        }
    }


}

