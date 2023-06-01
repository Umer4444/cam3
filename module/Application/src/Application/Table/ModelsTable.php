<?php

namespace Application\Table;

use ZfTable\AbstractTable;
// @todo check if this is used or not ?!
/**
 * @package Application\Table
 */
class ModelsTable extends AbstractTable
{

    protected $config;

    protected $headers = array(
        'id' => array('tableAlias' => 'u', 'title' => 'Id', 'width' => '15'),
        'username' => array('tableAlias' => 'u', 'title' => 'Username', 'filters' => 'text', 'width' => '15'),
        'display_name' => array('tableAlias' => 'u', 'title' => 'Display Name', 'filters' => 'text'),
        'email' => array('tableAlias' => 'u', 'title' => 'E-mail', 'filters' => 'text'),
        'online' => array('tableAlias' => 'u', 'title' => 'Online', 'width' => '15'),
        'allchips' => array('tableAlias' => 'u', 'title' => 'Chips'),
        'webchat_id' => array('tableAlias' => 's', 'title' => 'Broadcasting', 'width' => '15'),
        's_type' => array('tableAlias' => 's', 'title' => 'Type'),
        'Actions' => array('title' => 'Spy')
    );

    /**
     * Template Map
     * @var array
     */

    public function __construct()
    {
        return $this->config = array(
            'name' => 'Live Models Statistics',
            'showPagination' => true,
            'showQuickSearch' => false,
            'showItemPerPage' => true,
            'showColumnFilters' => true,
        );

    }

    public function init()
    {

        $this->getHeader('webchat_id')->getCell()->addDecorator('mapper', array(
            null => 'No',

        ));
        $this->getHeader('webchat_id')->getCell()->addDecorator('template', array(
            'template' => 'Yes',
        ))->addCondition('greaterthan', array('column' => 'webchat_id', 'values' => 0));

        $this->getHeader('online')->getCell()->addDecorator('callable', array(
            'callable' => function($context, $record){
                return '<span class="label label-'.($record['online'] ? 'success' : 'danger').'">&nbsp;</span>';
            }
        ));

        $this->getHeader('online')->getCell()->addAttr('style', 'text-align:center;');
        $this->getHeader('Actions')->getCell()->addDecorator('template', array(
            'template' => '<a href="/watch/%s/%s" target="_blank"><i class="icon-eye-open"></i></a>',
            'vars' => array('id', 'username')
        ));
        $this->getHeader('Actions')->getCell()->addAttr('style', 'text-align:center;');
    }

    // The filters could also be done with a parametrised query
    protected function initFilters($query)
    {

        if ($this->getParamAdapter()->getValueOfFilter('username')) {
            $username = $this->getParamAdapter()->getValueOfFilter('username');
            $query->where(
                new \Zend\Db\Sql\Predicate\Like('username', $username . "%")
            );
        }

        if ($this->getParamAdapter()->getValueOfFilter('display_name')) {
            $displayName = $this->getParamAdapter()->getValueOfFilter('display_name');
            $query->where(
                new \Zend\Db\Sql\Predicate\Like('display_name', $displayName . "%")
            );
        }

        if ($this->getParamAdapter()->getValueOfFilter('email')) {
            $email = $this->getParamAdapter()->getValueOfFilter('email');
            $query->where(
                new \Zend\Db\Sql\Predicate\Like('email', $email . "%")
            );
        }

    }

}