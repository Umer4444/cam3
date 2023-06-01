<?php

namespace Crud\Grid;

use Crud\Traits;

class AlbumsGrid extends BaseGrid\BaseAlbumsGrid
{

    use Traits\Date;
    use Traits\Cover;
    use Traits\Status;
    use Traits\Category;
    use Traits\Tags;

    protected $headers = array(
        'cover' => array(
            'title' => 'Cover',
            'width' => '100',
            'filters' => 'text',
        ),
        'category' => array(
            'title' => 'Category',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'description' => array(
            'title' => 'Description',
            'width' => '100',
            'filters' => 'text',
        ),
        'tags' => array(
            'title' => 'Tags',
            'width' => '100',
            'filters' => 'text',
        ),
        'uploaded_on' => array(
            'title' => 'Uploaded on',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'width' => '100',
            'filters' => 'text',
        ),
        'cost' => array(
            'title' => 'Cost',
            'width' => '100',
            'filters' => 'text',
        ),
        'photos' => array(
            'title' => '#photos',
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

    function onTotalPhotos()
    {
        $this->getHeader("photos")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return $record->getPhotos()->count();
            }
        ));
    }

}

