<?php
/**
 * This file is generated automatically for table "interactions". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseInteractionsGrid extends \ZfTable\AbstractTable
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
        'views' => array(
            'title' => 'Views',
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
        'reference_id' => array(
            'title' => 'Reference id',
            'width' => '100',
            'filters' => 'text',
        ),
        'entity' => array(
            'title' => 'Entity',
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
                return sprintf("<a href=\"/admin/crud/interactions/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/interactions/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('views');
        if ($value != null) {
            $query->where("views like '%".$value."%' ");
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

        $value = $this->getParamAdapter()->getValueOfFilter('reference_id');
        if ($value != null) {
            $query->where("reference_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('entity');
        if ($value != null) {
            $query->where("entity like '%".$value."%' ");
        }
    }


}

