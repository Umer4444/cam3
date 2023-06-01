<?php
/**
 * This file is generated automatically for table "announcements". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseAnnouncementsGrid extends \ZfTable\AbstractTable
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
        'id_moderator' => array(
            'title' => 'Id moderator',
            'width' => '100',
            'filters' => 'text',
        ),
        'text' => array(
            'title' => 'Text',
            'width' => '100',
            'filters' => 'text',
        ),
        'go_live' => array(
            'title' => 'Go live',
            'width' => '100',
            'filters' => 'text',
        ),
        'section' => array(
            'title' => 'Section',
            'width' => '100',
            'filters' => 'text',
        ),
        'active' => array(
            'title' => 'Active',
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
                return sprintf("<a href=\"/admin/crud/announcements/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/announcements/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_moderator');
        if ($value != null) {
            $query->where("id_moderator like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('text');
        if ($value != null) {
            $query->where("text like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('go_live');
        if ($value != null) {
            $query->where("go_live like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('section');
        if ($value != null) {
            $query->where("section like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('active');
        if ($value != null) {
            $query->where("active like '%".$value."%' ");
        }
    }


}

