<?php
/**
 * This file is generated automatically for table "user_role". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseUserRoleGrid extends \ZfTable\AbstractTable
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
        'role_id' => array(
            'title' => 'Role id',
            'width' => '100',
            'filters' => 'text',
        ),
        'parent_id' => array(
            'title' => 'Parent id',
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
                return sprintf("<a href=\"/admin/crud/user-role/update/%s\">Edit</a>", $record->getId());//id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/user-role/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('role_id');
        if ($value != null) {
            $query->where("role_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('parent_id');
        if ($value != null) {
            $query->where("parent_id like '%".$value."%' ");
        }
    }


}

