<?php
/**
 * This file is generated automatically for table "banners". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseBannersGrid extends \ZfTable\AbstractTable
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
        'id_user' => array(
            'title' => 'Id user',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_owner' => array(
            'title' => 'Id owner',
            'width' => '100',
            'filters' => 'text',
        ),
        'user_type' => array(
            'title' => 'User type',
            'width' => '100',
            'filters' => 'text',
        ),
        'content' => array(
            'title' => 'Content',
            'width' => '100',
            'filters' => 'text',
        ),
        'banner_zone' => array(
            'title' => 'Banner zone',
            'width' => '100',
            'filters' => 'text',
        ),
        'banner_size' => array(
            'title' => 'Banner size',
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
        'status' => array(
            'title' => 'Status',
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
                return sprintf("<a href=\"/admin/crud/banners/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/banners/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_user');
        if ($value != null) {
            $query->where("id_user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_owner');
        if ($value != null) {
            $query->where("id_owner like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_type');
        if ($value != null) {
            $query->where("user_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('content');
        if ($value != null) {
            $query->where("content like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('banner_zone');
        if ($value != null) {
            $query->where("banner_zone like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('banner_size');
        if ($value != null) {
            $query->where("banner_size like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('start_date');
        if ($value != null) {
            $query->where("start_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('end_date');
        if ($value != null) {
            $query->where("end_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }
    }


}

