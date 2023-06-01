<?php

namespace Crud\Grid;

use Crud\Traits;

class CallLogGrid extends \ZfTable\AbstractTable
{

    use Traits\Date;
    use Traits\Status;

    protected $config = array(
        'name' => '',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => false,
    );

    protected $headers = array(
        'from' => array(
            'title' => 'From',
            'width' => '100',
            'filters' => 'text',
            'tableAlias' => 'r',
        ),
        'duration' => array(
            'title' => 'Duration (in sec)',
            'width' => '100',
            'filters' => 'text',
            'tableAlias' => 'r',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
            'tableAlias' => 'r',
        ),
        'start' => array(
            'title' => 'Start',
            'width' => '100',
            'filters' => 'text',
            'tableAlias' => 'r',
        ),
        'end' => array(
            'title' => 'End',
            'width' => '100',
            'filters' => 'text',
            'tableAlias' => 'r',
        ),
    );

    public function init()
    {
        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 2) == "on") {
                $this->$method();
            }
        }
    }

    protected function initFilters($query)
    {

        $value = $this->getParamAdapter()->getValueOfFilter('account_sid');
        if ($value != null) {
            $query->where("account_sid like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('call_sid');
        if ($value != null) {
            $query->where("call_sid like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('from');
        if ($value != null) {
            $query->where("from like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('duration');
        if ($value != null) {
            $query->where("duration like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('start');
        if ($value != null) {
            $query->where("start like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('end');
        if ($value != null) {
            $query->where("end like '%".$value."%' ");
        }
    }

}

