<?php
/**
 * This file is generated automatically for table "pledge". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BasePledgeGrid extends \ZfTable\AbstractTable
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
        'id_category' => array(
            'title' => 'Id category',
            'width' => '100',
            'filters' => 'text',
        ),
        'title' => array(
            'title' => 'Title',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_resource' => array(
            'title' => 'Id resource',
            'width' => '100',
            'filters' => 'text',
        ),
        'content' => array(
            'title' => 'Content',
            'width' => '100',
            'filters' => 'text',
        ),
        'goal_amount' => array(
            'title' => 'Goal amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'goal_rep_share' => array(
            'title' => 'Goal rep share',
            'width' => '100',
            'filters' => 'text',
        ),
        'funding_type' => array(
            'title' => 'Funding type',
            'width' => '100',
            'filters' => 'text',
        ),
        'start_date' => array(
            'title' => 'Start date',
            'width' => '100',
            'filters' => 'text',
        ),
        'end_date' => array(
            'title' => 'End date',
            'width' => '100',
            'filters' => 'text',
        ),
        'duration' => array(
            'title' => 'Duration',
            'width' => '100',
            'filters' => 'text',
        ),
        'duration_days' => array(
            'title' => 'Duration days',
            'width' => '100',
            'filters' => 'text',
        ),
        'duration_type' => array(
            'title' => 'Duration type',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'rating' => array(
            'title' => 'Rating',
            'width' => '100',
            'filters' => 'text',
        ),
        'votes' => array(
            'title' => 'Votes',
            'width' => '100',
            'filters' => 'text',
        ),
        'likes' => array(
            'title' => 'Likes',
            'width' => '100',
            'filters' => 'text',
        ),
        'dislikes' => array(
            'title' => 'Dislikes',
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
                return sprintf("<a href=\"/admin/crud/pledge/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/pledge/delete/%s\">Delete</a>", $record->getId());
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

        $value = $this->getParamAdapter()->getValueOfFilter('id_category');
        if ($value != null) {
            $query->where("id_category like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('title');
        if ($value != null) {
            $query->where("title like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_resource');
        if ($value != null) {
            $query->where("id_resource like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('content');
        if ($value != null) {
            $query->where("content like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('goal_amount');
        if ($value != null) {
            $query->where("goal_amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('goal_rep_share');
        if ($value != null) {
            $query->where("goal_rep_share like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('funding_type');
        if ($value != null) {
            $query->where("funding_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('start_date');
        if ($value != null) {
            $query->where("start_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('end_date');
        if ($value != null) {
            $query->where("end_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('duration');
        if ($value != null) {
            $query->where("duration like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('duration_days');
        if ($value != null) {
            $query->where("duration_days like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('duration_type');
        if ($value != null) {
            $query->where("duration_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rating');
        if ($value != null) {
            $query->where("rating like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('votes');
        if ($value != null) {
            $query->where("votes like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('likes');
        if ($value != null) {
            $query->where("likes like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('dislikes');
        if ($value != null) {
            $query->where("dislikes like '%".$value."%' ");
        }
    }


}

