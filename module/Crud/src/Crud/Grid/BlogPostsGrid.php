<?php

namespace Crud\Grid;

use Crud\Traits;

class BlogPostsGrid extends BaseGrid\BaseBlogPostsGrid
{

    use Traits\Status;

    protected $headers = array(
        'title' => array(
            'title' => 'Title',
            'width' => '100',
            'filters' => 'text'
        ),
        'content' => array(
            'title' => 'Content',
            'width' => '100',
            'filters' => 'text'
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text'
        ),
        'date' => array(
            'title' => 'Date',
            'width' => '100',
            'filters' => 'text'
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

    function __construct()
    {
        unset($this->config['showColumnFilters']);
    }

    public function init()
    {

        parent::init();

        $this->getHeader("content")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return substr(strip_tags($record->getContent()), 0, 200).' ...';
            }
        ));

    }

}

