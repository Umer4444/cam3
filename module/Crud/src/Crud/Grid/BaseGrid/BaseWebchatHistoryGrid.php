<?php
/**
 * This file is generated automatically for table "webchat_history". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseWebchatHistoryGrid extends \ZfTable\AbstractTable
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
        'user_id' => array(
            'title' => 'User id',
            'width' => '100',
            'filters' => 'text',
        ),
        'start' => array(
            'title' => 'Start',
            'width' => '100',
            'filters' => 'text',
        ),
        'end' => array(
            'title' => 'End',
            'width' => '100',
            'filters' => 'text',
        ),
        'rating_surround' => array(
            'title' => 'Rating surround',
            'width' => '100',
            'filters' => 'text',
        ),
        'votes_surround' => array(
            'title' => 'Votes surround',
            'width' => '100',
            'filters' => 'text',
        ),
        'rating_appearance' => array(
            'title' => 'Rating appearance',
            'width' => '100',
            'filters' => 'text',
        ),
        'votes_appearance' => array(
            'title' => 'Votes appearance',
            'width' => '100',
            'filters' => 'text',
        ),
        'rating_sound' => array(
            'title' => 'Rating sound',
            'width' => '100',
            'filters' => 'text',
        ),
        'votes_sound' => array(
            'title' => 'Votes sound',
            'width' => '100',
            'filters' => 'text',
        ),
        'rating_light' => array(
            'title' => 'Rating light',
            'width' => '100',
            'filters' => 'text',
        ),
        'votes_light' => array(
            'title' => 'Votes light',
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
                return sprintf("<a href=\"/admin/crud/webchat-history/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/webchat-history/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_id');
        if ($value != null) {
            $query->where("user_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('start');
        if ($value != null) {
            $query->where("start like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('end');
        if ($value != null) {
            $query->where("end like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rating_surround');
        if ($value != null) {
            $query->where("rating_surround like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('votes_surround');
        if ($value != null) {
            $query->where("votes_surround like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rating_appearance');
        if ($value != null) {
            $query->where("rating_appearance like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('votes_appearance');
        if ($value != null) {
            $query->where("votes_appearance like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rating_sound');
        if ($value != null) {
            $query->where("rating_sound like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('votes_sound');
        if ($value != null) {
            $query->where("votes_sound like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rating_light');
        if ($value != null) {
            $query->where("rating_light like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('votes_light');
        if ($value != null) {
            $query->where("votes_light like '%".$value."%' ");
        }
    }


}

