<?php
/**
 * This file is generated automatically for table "blog_posts". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseBlogPostsGrid extends \ZfTable\AbstractTable
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
        'category' => array(
            'title' => 'Category',
            'width' => '100',
            'filters' => 'text',
        ),
        'created_by' => array(
            'title' => 'Created by',
            'width' => '100',
            'filters' => 'text',
        ),
        'user' => array(
            'title' => 'User',
            'width' => '100',
            'filters' => 'text',
        ),
        'title' => array(
            'title' => 'Title',
            'width' => '100',
            'filters' => 'text',
        ),
        'content' => array(
            'title' => 'Content',
            'width' => '100',
            'filters' => 'text',
        ),
        'tags' => array(
            'title' => 'Tags',
            'width' => '100',
            'filters' => 'text',
        ),
        'slug' => array(
            'title' => 'Slug',
            'width' => '100',
            'filters' => 'text',
        ),
        'posted_on' => array(
            'title' => 'Posted on',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'featured' => array(
            'title' => 'Featured',
            'width' => '100',
            'filters' => 'text',
        ),
        'reposts' => array(
            'title' => 'Reposts',
            'width' => '100',
            'filters' => 'text',
        ),
        'repost_date' => array(
            'title' => 'Repost date',
            'width' => '100',
            'filters' => 'text',
        ),
        'pinned' => array(
            'title' => 'Pinned',
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
                return sprintf("<a href=\"/admin/crud/blog-posts/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/blog-posts/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('category');
        if ($value != null) {
            $query->where("category like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('created_by');
        if ($value != null) {
            $query->where("created_by like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user');
        if ($value != null) {
            $query->where("user like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('title');
        if ($value != null) {
            $query->where("title like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('content');
        if ($value != null) {
            $query->where("content like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('tags');
        if ($value != null) {
            $query->where("tags like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('slug');
        if ($value != null) {
            $query->where("slug like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('posted_on');
        if ($value != null) {
            $query->where("posted_on like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('featured');
        if ($value != null) {
            $query->where("featured like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reposts');
        if ($value != null) {
            $query->where("reposts like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('repost_date');
        if ($value != null) {
            $query->where("repost_date like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('pinned');
        if ($value != null) {
            $query->where("pinned like '%".$value."%' ");
        }
    }


}

