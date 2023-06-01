<?php

namespace Crud\Grid;

use Crud\Traits;

class VideoGrid extends BaseGrid\BaseVideoGrid
{

    use Traits\Status;
    use Traits\Tags;
    use Traits\Filename;
    use Traits\Cover;

    protected $config = array(
        'name' => '',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => true,
    );

    protected $headers = array(
        'cover' => array(
            'title' => 'Cover',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'title' => array(
            'title' => 'Title',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'tags' => array(
            'title' => 'Tags',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'filename' => array(
            'title' => 'Filename',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'duration' => array(
            'title' => 'Duration (in sec)',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'tableAlias' => 'r',
            'filters' => [],
        ),
        'cast' => array(
            'title' => 'Cast',
            'width' => '100',
            'tableAlias' => 'r',
        ),
        'edit' => array(
            'title' => 'Edit',
            'width' => '100'
        ),
        'delete' => array(
            'title' => 'Delete',
            'width' => '100'
        )
    );

    function init()
    {
        parent::init();
        $this->headers['status']['filters'] = [null => 'all']+ self::getStatusValues();
    }

}

