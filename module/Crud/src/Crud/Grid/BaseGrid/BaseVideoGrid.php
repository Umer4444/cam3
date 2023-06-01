<?php
/**
 * This file is generated automatically for table "video". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseVideoGrid extends \ZfTable\AbstractTable
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
        'user' => array(
            'title' => 'User',
            'width' => '100',
            'filters' => 'text',
        ),
        'category_id' => array(
            'title' => 'Category id',
            'width' => '100',
            'filters' => 'text',
        ),
        'cover' => array(
            'title' => 'Cover',
            'width' => '100',
            'filters' => 'text',
        ),
        'reference_id' => array(
            'title' => 'Reference id',
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
        'cost' => array(
            'title' => 'Cost',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'cast' => array(
            'title' => 'Cast',
            'width' => '100',
            'filters' => 'text',
        ),
        'tags' => array(
            'title' => 'Tags',
            'width' => '100',
            'filters' => 'text',
        ),
        'filename' => array(
            'title' => 'Filename',
            'width' => '100',
            'filters' => 'text',
        ),
        'duration' => array(
            'title' => 'Duration',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'uploaded_on' => array(
            'title' => 'Uploaded on',
            'width' => '100',
            'filters' => 'text',
        ),
        'entity' => array(
            'title' => 'Entity',
            'width' => '100',
            'filters' => 'text',
        ),
        'start_date' => array(
            'title' => 'Start date',
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
                return sprintf("<a href=\"/admin/crud/video/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/video/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user');
        if ($value != null) {
            $query->where("user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('category_id');
        if ($value != null) {
            $query->where("category_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('cover');
        if ($value != null) {
            $query->where("cover like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reference_id');
        if ($value != null) {
            $query->where("reference_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('title');
        if ($value != null) {
            $query->where("title like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('cost');
        if ($value != null) {
            $query->where("cost like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('description');
        if ($value != null) {
            $query->where("description like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('cast');
        if ($value != null) {
            $query->where("cast like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('tags');
        if ($value != null) {
            $query->where("tags like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('filename');
        if ($value != null) {
            $query->where("filename like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('duration');
        if ($value != null) {
            $query->where("duration like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("r.status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('uploaded_on');
        if ($value != null) {
            $query->where("uploaded_on like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('entity');
        if ($value != null) {
            $query->where("entity like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('start_date');
        if ($value != null) {
            $query->where("start_date like '%".$value."%' ");
        }
    }

}