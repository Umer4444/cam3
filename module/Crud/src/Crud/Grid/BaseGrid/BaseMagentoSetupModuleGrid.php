<?php
/**
 * This file is generated automatically for table "magento_setup_module". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Grid\BaseGrid;

class BaseMagentoSetupModuleGrid extends \ZfTable\AbstractTable
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
        'module' => array(
            'title' => 'Module',
            'width' => '100',
            'filters' => 'text',
        ),
        'schema_version' => array(
            'title' => 'Schema version',
            'width' => '100',
            'filters' => 'text',
        ),
        'data_version' => array(
            'title' => 'Data version',
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
                return sprintf("<a href=\"/admin/crud/magento-setup-module/update/%s\">Edit</a>", $record->getId());//module
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/magento-setup-module/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('module');
        if ($value != null) {
            $query->where("module like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('schema_version');
        if ($value != null) {
            $query->where("schema_version like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('data_version');
        if ($value != null) {
            $query->where("data_version like '%".$value."%' ");
        }
    }


}

