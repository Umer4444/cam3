<?php
/**
 * This file is generated automatically for table "moderator". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseModeratorGrid extends \ZfTable\AbstractTable
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
        'username' => array(
            'title' => 'Username',
            'width' => '100',
            'filters' => 'text',
        ),
        'email' => array(
            'title' => 'Email',
            'width' => '100',
            'filters' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_login' => array(
            'title' => 'Last login',
            'width' => '100',
            'filters' => 'text',
        ),
        'active' => array(
            'title' => 'Active',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_activity' => array(
            'title' => 'Last activity',
            'width' => '100',
            'filters' => 'text',
        ),
        'online' => array(
            'title' => 'Online',
            'width' => '100',
            'filters' => 'text',
        ),
        'session_id' => array(
            'title' => 'Session id',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_notification' => array(
            'title' => 'Last notification',
            'width' => '100',
            'filters' => 'text',
        ),
        'notification_email' => array(
            'title' => 'Notification email',
            'width' => '100',
            'filters' => 'text',
        ),
        'reset_code' => array(
            'title' => 'Reset code',
            'width' => '100',
            'filters' => 'text',
        ),
        'screen_name' => array(
            'title' => 'Screen name',
            'width' => '100',
            'filters' => 'text',
        ),
        'phone' => array(
            'title' => 'Phone',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_cover' => array(
            'title' => 'Id cover',
            'width' => '100',
            'filters' => 'text',
        ),
        'other_contact' => array(
            'title' => 'Other contact',
            'width' => '100',
            'filters' => 'text',
        ),
        'other_id' => array(
            'title' => 'Other id',
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
                return sprintf("<a href=\"/admin/crud/moderator/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/moderator/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('username');
        if ($value != null) {
            $query->where("username like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('email');
        if ($value != null) {
            $query->where("email like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('password');
        if ($value != null) {
            $query->where("password like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_login');
        if ($value != null) {
            $query->where("last_login like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('active');
        if ($value != null) {
            $query->where("active like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_activity');
        if ($value != null) {
            $query->where("last_activity like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('online');
        if ($value != null) {
            $query->where("online like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('session_id');
        if ($value != null) {
            $query->where("session_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_notification');
        if ($value != null) {
            $query->where("last_notification like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('notification_email');
        if ($value != null) {
            $query->where("notification_email like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reset_code');
        if ($value != null) {
            $query->where("reset_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('screen_name');
        if ($value != null) {
            $query->where("screen_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('phone');
        if ($value != null) {
            $query->where("phone like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_cover');
        if ($value != null) {
            $query->where("id_cover like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('other_contact');
        if ($value != null) {
            $query->where("other_contact like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('other_id');
        if ($value != null) {
            $query->where("other_id like '%".$value."%' ");
        }
    }


}

