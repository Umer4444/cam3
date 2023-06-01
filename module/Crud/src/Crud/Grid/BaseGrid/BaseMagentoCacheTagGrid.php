<?php
/**
 * This file is generated automatically for table "magento_cache_tag". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseMagentoCacheTagGrid extends \ZfTable\AbstractTable
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
        'tag' => array(
            'title' => 'Tag',
            'width' => '100',
            'filters' => 'text',
        ),
        'cache_id' => array(
            'title' => 'Cache id',
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
                return sprintf("<a href=\"/admin/crud/magento-cache-tag/update/%s\">Edit</a>", $record->getId());//cache_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/magento-cache-tag/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('tag');
        if ($value != null) {
            $query->where("tag like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('cache_id');
        if ($value != null) {
            $query->where("cache_id like '%".$value."%' ");
        }
    }


}

