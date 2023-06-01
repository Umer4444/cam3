<?php
/**
 * This file is generated automatically for table "video_to_categories". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseVideoToCategoriesGrid extends \ZfTable\AbstractTable
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
        'id_video' => array(
            'title' => 'Id video',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_category' => array(
            'title' => 'Id category',
            'width' => '100',
            'filters' => 'text',
        ),
        'sort' => array(
            'title' => 'Sort',
            'width' => '100',
            'filters' => 'text',
        ),
        'videos_id' => array(
            'title' => 'Videos id',
            'width' => '100',
            'filters' => 'text',
        ),
        'categories_id' => array(
            'title' => 'Categories id',
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
                return sprintf("<a href=\"/admin/crud/video-to-categories/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/video-to-categories/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_video');
        if ($value != null) {
            $query->where("id_video like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_category');
        if ($value != null) {
            $query->where("id_category like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('sort');
        if ($value != null) {
            $query->where("sort like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('videos_id');
        if ($value != null) {
            $query->where("videos_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('categories_id');
        if ($value != null) {
            $query->where("categories_id like '%".$value."%' ");
        }
    }


}

