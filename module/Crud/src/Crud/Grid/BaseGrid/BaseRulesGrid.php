<?php
/**
 * This file is generated automatically for table "rules". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseRulesGrid extends \ZfTable\AbstractTable
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
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'title' => array(
            'title' => 'Title',
            'width' => '100',
            'filters' => 'text',
        ),
        'free_chat' => array(
            'title' => 'Free chat',
            'width' => '100',
            'filters' => 'text',
        ),
        'paid_chat' => array(
            'title' => 'Paid chat',
            'width' => '100',
            'filters' => 'text',
        ),
        'videos' => array(
            'title' => 'Videos',
            'width' => '100',
            'filters' => 'text',
        ),
        'photos' => array(
            'title' => 'Photos',
            'width' => '100',
            'filters' => 'text',
        ),
        'fine' => array(
            'title' => 'Fine',
            'width' => '100',
            'filters' => 'text',
        ),
        'fine_text' => array(
            'title' => 'Fine text',
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
                return sprintf("<a href=\"/admin/crud/rules/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/rules/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('title');
        if ($value != null) {
            $query->where("title like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('free_chat');
        if ($value != null) {
            $query->where("free_chat like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('paid_chat');
        if ($value != null) {
            $query->where("paid_chat like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('videos');
        if ($value != null) {
            $query->where("videos like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('photos');
        if ($value != null) {
            $query->where("photos like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('fine');
        if ($value != null) {
            $query->where("fine like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('fine_text');
        if ($value != null) {
            $query->where("fine_text like '%".$value."%' ");
        }
    }


}

