<?php
/**
 * This file is generated automatically for table "followers". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseFollowersGrid extends \ZfTable\AbstractTable
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
        'id_follower' => array(
            'title' => 'Id follower',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_followed' => array(
            'title' => 'Id followed',
            'width' => '100',
            'filters' => 'text',
        ),
        'added' => array(
            'title' => 'Added',
            'width' => '100',
            'filters' => 'text',
        ),
        'new_photo' => array(
            'title' => 'New photo',
            'width' => '100',
            'filters' => 'text',
        ),
        'new_video' => array(
            'title' => 'New video',
            'width' => '100',
            'filters' => 'text',
        ),
        'when_online' => array(
            'title' => 'When online',
            'width' => '100',
            'filters' => 'text',
        ),
        'blog' => array(
            'title' => 'Blog',
            'width' => '100',
            'filters' => 'text',
        ),
        'pledge' => array(
            'title' => 'Pledge',
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
                return sprintf("<a href=\"/admin/crud/followers/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/followers/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_follower');
        if ($value != null) {
            $query->where("id_follower like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_followed');
        if ($value != null) {
            $query->where("id_followed like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('added');
        if ($value != null) {
            $query->where("added like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('new_photo');
        if ($value != null) {
            $query->where("new_photo like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('new_video');
        if ($value != null) {
            $query->where("new_video like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('when_online');
        if ($value != null) {
            $query->where("when_online like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('blog');
        if ($value != null) {
            $query->where("blog like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('pledge');
        if ($value != null) {
            $query->where("pledge like '%".$value."%' ");
        }
    }


}

