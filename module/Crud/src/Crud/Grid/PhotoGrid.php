<?php

namespace Crud\Grid;

use Crud\Traits;

class PhotoGrid extends BaseGrid\BasePhotoGrid
{

    use Traits\Filename;
    use Traits\Status;
    use Traits\Date;

    protected $config = array(
        'name' => '',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => false,
    );

    protected $headers = array(
        'filename' => array(
            'title' => 'Filename',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'caption' => array(
            'title' => 'Caption',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'uploaded_on' => array(
            'title' => 'Uploaded On',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => [],
            'tableAlias' => 'r'
        ),
        'edit' => array(
            'title' => 'Edit',
            'width' => '100'
        ),
        'delete' => array(
            'title' => 'Delete',
            'width' => '100'
        ),
    );

    function init()
    {
        parent::init();
        $this->headers['status']['filters'] = [null => 'all']+ self::getStatusValues();
    }

}

