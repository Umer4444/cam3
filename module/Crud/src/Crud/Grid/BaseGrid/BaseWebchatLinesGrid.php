<?php
/**
 * This file is generated automatically for table "webchat_lines". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseWebchatLinesGrid extends \ZfTable\AbstractTable
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
        'model_id' => array(
            'title' => 'Model id',
            'width' => '100',
            'filters' => 'text',
        ),
        'author' => array(
            'title' => 'Author',
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
        'text' => array(
            'title' => 'Text',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'autoresponse' => array(
            'title' => 'Autoresponse',
            'width' => '100',
            'filters' => 'text',
        ),
        'responded' => array(
            'title' => 'Responded',
            'width' => '100',
            'filters' => 'text',
        ),
        'ts' => array(
            'title' => 'Ts',
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
                return sprintf("<a href=\"/admin/crud/webchat-lines/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/webchat-lines/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('model_id');
        if ($value != null) {
            $query->where("model_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('author');
        if ($value != null) {
            $query->where("author like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gravatar');
        if ($value != null) {
            $query->where("gravatar like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_model');
        if ($value != null) {
            $query->where("id_model like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('text');
        if ($value != null) {
            $query->where("text like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('autoresponse');
        if ($value != null) {
            $query->where("autoresponse like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('responded');
        if ($value != null) {
            $query->where("responded like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ts');
        if ($value != null) {
            $query->where("ts like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chat_font');
        if ($value != null) {
            $query->where("chat_font like '%".$value."%' ");
        }
    }


}

