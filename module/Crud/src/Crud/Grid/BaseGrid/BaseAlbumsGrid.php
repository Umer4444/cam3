<?php
/**
 * This file is generated automatically for table "albums". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseAlbumsGrid extends \ZfTable\AbstractTable
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
        'model_id' => array(
            'title' => 'Model id',
            'width' => '100',
            'filters' => 'text',
        ),
        'cover' => array(
            'title' => 'Cover',
            'width' => '100',
            'filters' => 'text',
        ),
        'category' => array(
            'title' => 'Category',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'tags' => array(
            'title' => 'Tags',
            'width' => '100',
            'filters' => 'text',
        ),
        'type' => array(
            'title' => 'Type',
            'width' => '100',
            'filters' => 'text',
        ),
        'total_views' => array(
            'title' => 'Total views',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_resource' => array(
            'title' => 'Id resource',
            'width' => '100',
            'filters' => 'text',
        ),
        'uploaded_on' => array(
            'title' => 'Added',
            'width' => '100',
            'filters' => 'text',
        ),
        'updated' => array(
            'title' => 'Updated',
            'width' => '100',
            'filters' => 'text',
        ),
        'active' => array(
            'title' => 'Active',
            'width' => '100',
            'filters' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'width' => '100',
            'filters' => 'text',
        ),
        'viewable' => array(
            'title' => 'Viewable',
            'width' => '100',
            'filters' => 'text',
        ),
        'amount' => array(
            'title' => 'Amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'parent_id' => array(
            'title' => 'Parent id',
            'width' => '100',
            'filters' => 'text',
        ),
        'total_photos' => array(
            'title' => 'Total photos',
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
                return sprintf("<a href=\"/admin/crud/albums/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/albums/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('model_id');
        if ($value != null) {
            $query->where("model_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('cover');
        if ($value != null) {
            $query->where("cover like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('category');
        if ($value != null) {
            $query->where("category like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('description');
        if ($value != null) {
            $query->where("description like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('tags');
        if ($value != null) {
            $query->where("tags like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('type');
        if ($value != null) {
            $query->where("type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_resource');
        if ($value != null) {
            $query->where("id_resource like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('uploaded_on');
        if ($value != null) {
            $query->where("uploaded_on like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('updated');
        if ($value != null) {
            $query->where("updated like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('active');
        if ($value != null) {
            $query->where("active like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('password');
        if ($value != null) {
            $query->where("password like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('viewable');
        if ($value != null) {
            $query->where("viewable like '%".$value."%' ");
        }


        $value = $this->getParamAdapter()->getValueOfFilter('amount');
        if ($value != null) {
            $query->where("amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('parent_id');
        if ($value != null) {
            $query->where("parent_id like '%".$value."%' ");
        }

    }


}

