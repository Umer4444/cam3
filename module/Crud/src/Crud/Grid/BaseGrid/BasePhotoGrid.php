<?php
/**
 * This file is generated automatically for table "photos". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BasePhotoGrid extends \ZfTable\AbstractTable
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
        'album_id' => array(
            'title' => 'Album id',
            'width' => '100',
            'filters' => 'text',
        ),
        'reference_id' => array(
            'title' => 'Reference id',
            'width' => '100',
            'filters' => 'text',
        ),
        'filename' => array(
            'title' => 'Filename',
            'width' => '100',
            'filters' => 'text',
        ),
        'caption' => array(
            'title' => 'Caption',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
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
        'position' => array(
            'title' => 'Position',
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
                return sprintf("<a href=\"/admin/crud/photo/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/photo/delete/%s\">Delete</a>", $record->getId());
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

        $value = $this->getParamAdapter()->getValueOfFilter('album_id');
        if ($value != null) {
            $query->where("album_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reference_id');
        if ($value != null) {
            $query->where("reference_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('filename');
        if ($value != null) {
            $query->where("filename like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('caption');
        if ($value != null) {
            $query->where("caption like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('uploaded_on');
        if ($value != null) {
            $query->where("uploaded_on like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('position');
        if ($value != null) {
            $query->where("position like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('entity');
        if ($value != null) {
            $query->where("entity like '%".$value."%' ");
        }
    }


}

