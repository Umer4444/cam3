<?php

namespace Images\Model;

use ZfTable\AbstractTable;
use ZfTable\Params;
use ZfTable\Params\AdapterArrayObject;
use ZfTable\Table\Exception;

class imagesTable extends AbstractTable
{
    /**
     * Params adapter which responsible for universal mapping parameters from different
     * source (default params, Data Table params, JGrid params)
     * @var ParamAdapterInterface
     */
    protected $paramAdapter;

    protected $config = array(
        'showPagination' => true,
        'showQuickSearch' => false,
        'itemCountPerPage' => 10,
        'valuesOfItemPerPage' => array(5, 10, 20, 50, 100, 200),
        'showColumnFilters' => false,
        'rowAction' => '/images/updateRow',

    );

    protected $headers = array(
        'id' => array('title' => 'id', 'filter-class' => 'hidden', 'sortable' => false),
        'name' => array('title' => 'Name', 'filters' => 'text'),
        'description' => array('title' => 'Description', 'filters' => 'text', 'width' => '60%', 'editable' => true),
        'preview' => array('title' => 'Preview', 'width' => '60', 'sortable' => false),
        'edit' => array('title' => 'Edit', 'width' => '80', 'sortable' => false),
    );

    public function init()
    {

        $this->getHeader('name')->getCell()->addDecorator('editable');

        $this->getRow()->addDecorator('varattr', array('name' => 'data-row', 'value' => '%s', 'vars' => array('id')));

        $this->getHeader('description')->getCell()->addDecorator('editable');

        $this->getHeader('id')->getCell()->addDecorator('varattr',
            array('class' => 'hidden'));
        $this->getHeader('id')->addClass('hidden');

        $this->getHeader('preview')->getCell()->addDecorator('template', array(
            'template' => '<a href="#preview" role="button"
            data-toggle="modal" data-src="/uploads/images/%s.%s" class="prev" title="Preview">' .
                '<img src="/uploads/images/%s.%s" alt="%s" data-file="/uploads/images/%s.%s" width="60px" height="60px"/></a>',
            'vars' => array('id', 'extension', 'id', 'extension', 'name', 'id', 'extension')
        ));
        $this->getHeader('edit')->getCell()->addDecorator('template', array(
            'template' => '<a href="/images/edit/%s" class="btn icon-pencil" title="Edit"></a> '
                . ' <a href="#delete" role="button" class="btn icon-trash"
            data-toggle="modal" data-id="%s" id="trash" title="Delete" ></a>',
            'vars' => array('id', 'id')
        ));

    }

    protected function initFilters(\Zend\Db\Sql\Select $query)
    {

        if ($value = $this->getParamAdapter()->getValueOfFilter('name')) {
            $query->where("name like '%" . $value . "%' ");
        }
        if ($value = $this->getParamAdapter()->getValueOfFilter('description')) {
            $query->where("description like '%" . $value . "%' ");
        }

    }

    /**
     * Return Params adapter which responsible for universal mapping parameters from diffrent
     * source (default params, Data Table params, JGrid params)
     * @return ParamAdapterInterface
     */
    public function getParamAdapter()
    {
        return $this->paramAdapter;
    }

    /**
     *
     * @param  \ZfTable\Params\AdapterInterface $paramAdapter
     * @throws \InvalidArgumentException
     */
    public function setParamAdapter($params)
    {
        if ($params instanceof \ZfTable\Params\AdapterInterface) {
            $this->paramAdapter = $params;
        } elseif ($params instanceof \Zend\Stdlib\Parameters) {
            $this->paramAdapter = new AdapterArrayObject($params);
        } else {
            throw new Exception\InvalidArgumentException
            ('Parameter must be instance of AdapterInterface or \Zend\Stdlib\Parameters');
        }
        $this->paramAdapter->setTable($this);
        $this->paramAdapter->init();
    }

}
