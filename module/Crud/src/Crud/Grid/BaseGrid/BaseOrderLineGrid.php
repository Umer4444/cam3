<?php
/**
 * This file is generated automatically for table "order_line". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseOrderLineGrid extends \ZfTable\AbstractTable
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
        'line_id' => array(
            'title' => 'Line id',
            'width' => '100',
            'filters' => 'text',
        ),
        'order_id' => array(
            'title' => 'Order id',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'quantity' => array(
            'title' => 'Quantity',
            'width' => '100',
            'filters' => 'text',
        ),
        'price' => array(
            'title' => 'Price',
            'width' => '100',
            'filters' => 'text',
        ),
        'tax' => array(
            'title' => 'Tax',
            'width' => '100',
            'filters' => 'text',
        ),
        'options' => array(
            'title' => 'Options',
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
                return sprintf("<a href=\"/admin/crud/order-line/update/%s\">Edit</a>", $record->getId());//line_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/order-line/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('line_id');
        if ($value != null) {
            $query->where("line_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('order_id');
        if ($value != null) {
            $query->where("order_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('description');
        if ($value != null) {
            $query->where("description like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('quantity');
        if ($value != null) {
            $query->where("quantity like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('price');
        if ($value != null) {
            $query->where("price like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('tax');
        if ($value != null) {
            $query->where("tax like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('options');
        if ($value != null) {
            $query->where("options like '%".$value."%' ");
        }
    }


}

