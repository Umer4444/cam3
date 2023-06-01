<?php
/**
 * This file is generated automatically for table "messages". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseMessagesGrid extends \ZfTable\AbstractTable
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
        'id_sender' => array(
            'title' => 'Id sender',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_receiver' => array(
            'title' => 'Id receiver',
            'width' => '100',
            'filters' => 'text',
        ),
        'sender_type' => array(
            'title' => 'Sender type',
            'width' => '100',
            'filters' => 'text',
        ),
        'receiver_type' => array(
            'title' => 'Receiver type',
            'width' => '100',
            'filters' => 'text',
        ),
        'subject' => array(
            'title' => 'Subject',
            'width' => '100',
            'filters' => 'text',
        ),
        'message' => array(
            'title' => 'Message',
            'width' => '100',
            'filters' => 'text',
        ),
        'inbox' => array(
            'title' => 'Inbox',
            'width' => '100',
            'filters' => 'text',
        ),
        'outbox' => array(
            'title' => 'Outbox',
            'width' => '100',
            'filters' => 'text',
        ),
        'send_date' => array(
            'title' => 'Send date',
            'width' => '100',
            'filters' => 'text',
        ),
        'read' => array(
            'title' => 'Read',
            'width' => '100',
            'filters' => 'text',
        ),
        'tip' => array(
            'title' => 'Tip',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
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
                return sprintf("<a href=\"/admin/crud/messages/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/messages/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_sender');
        if ($value != null) {
            $query->where("id_sender like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_receiver');
        if ($value != null) {
            $query->where("id_receiver like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('sender_type');
        if ($value != null) {
            $query->where("sender_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('receiver_type');
        if ($value != null) {
            $query->where("receiver_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('subject');
        if ($value != null) {
            $query->where("subject like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('message');
        if ($value != null) {
            $query->where("message like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('inbox');
        if ($value != null) {
            $query->where("inbox like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('outbox');
        if ($value != null) {
            $query->where("outbox like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('send_date');
        if ($value != null) {
            $query->where("send_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('read');
        if ($value != null) {
            $query->where("read like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('tip');
        if ($value != null) {
            $query->where("tip like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }
    }


}

