<?php
/**
 * This file is generated automatically for table "rb_comments". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseRbCommentsGrid extends \ZfTable\AbstractTable
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
        'parent_id' => array(
            'title' => 'Parent id',
            'width' => '100',
            'filters' => 'text',
        ),
        'thread' => array(
            'title' => 'Thread',
            'width' => '100',
            'filters' => 'text',
        ),
        'uri' => array(
            'title' => 'Uri',
            'width' => '100',
            'filters' => 'text',
        ),
        'author' => array(
            'title' => 'Author',
            'width' => '100',
            'filters' => 'text',
        ),
        'contact' => array(
            'title' => 'Contact',
            'width' => '100',
            'filters' => 'text',
        ),
        'content' => array(
            'title' => 'Content',
            'width' => '100',
            'filters' => 'text',
        ),
        'visible' => array(
            'title' => 'Visible',
            'width' => '100',
            'filters' => 'text',
        ),
        'spam' => array(
            'title' => 'Spam',
            'width' => '100',
            'filters' => 'text',
        ),
        'published_on' => array(
            'title' => 'Published on',
            'width' => '100',
            'filters' => 'text',
        ),
        'user_domain_id' => array(
            'title' => 'User domain id',
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
                return sprintf("<a href=\"/admin/crud/rb-comments/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/rb-comments/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('parent_id');
        if ($value != null) {
            $query->where("parent_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('thread');
        if ($value != null) {
            $query->where("thread like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('uri');
        if ($value != null) {
            $query->where("uri like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('author');
        if ($value != null) {
            $query->where("author like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('contact');
        if ($value != null) {
            $query->where("contact like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('content');
        if ($value != null) {
            $query->where("content like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('visible');
        if ($value != null) {
            $query->where("visible like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('spam');
        if ($value != null) {
            $query->where("spam like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('published_on');
        if ($value != null) {
            $query->where("published_on like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('user_domain_id');
        if ($value != null) {
            $query->where("user_domain_id like '%".$value."%' ");
        }
    }


}

