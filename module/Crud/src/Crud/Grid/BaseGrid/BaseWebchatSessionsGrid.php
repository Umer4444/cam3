<?php
/**
 * This file is generated automatically for table "webchat_sessions". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseWebchatSessionsGrid extends \ZfTable\AbstractTable
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
        'chat_type' => array(
            'title' => 'Chat type',
            'width' => '100',
            'filters' => 'text',
        ),
        'timer' => array(
            'title' => 'Timer',
            'width' => '100',
            'filters' => 'text',
        ),
        'cameras' => array(
            'title' => 'Cameras',
            'width' => '100',
            'filters' => 'text',
        ),
        'pending_users' => array(
            'title' => 'Pending users',
            'width' => '100',
            'filters' => 'text',
        ),
        'users_count' => array(
            'title' => 'Users count',
            'width' => '100',
            'filters' => 'text',
        ),
        'session_identifier' => array(
            'title' => 'Session identifier',
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
                return sprintf("<a href=\"/admin/crud/webchat-sessions/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/webchat-sessions/delete/%s\">Delete</a>", $record->getId());
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

        $value = $this->getParamAdapter()->getValueOfFilter('chat_type');
        if ($value != null) {
            $query->where("chat_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('timer');
        if ($value != null) {
            $query->where("timer like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('cameras');
        if ($value != null) {
            $query->where("cameras like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('pending_users');
        if ($value != null) {
            $query->where("pending_users like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('users_count');
        if ($value != null) {
            $query->where("users_count like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('session_identifier');
        if ($value != null) {
            $query->where("session_identifier like '%".$value."%' ");
        }
    }


}

