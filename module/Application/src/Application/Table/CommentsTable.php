<?php

namespace Application\Table;

use ZfTable\AbstractTable;

/**
 * Class CommentsTable
 * @package Application\Table
 */
class CommentsTable extends AbstractTable
{

    protected $config;

    //Definition of headers
    protected $headers = array(
        'id' => array('tableAlias' => 'r', 'title' => 'Id', 'width' => '15'),
        'author' => array('tableAlias' => 'r', 'title' => 'Author'),
        'contact' => array('tableAlias' => 'r', 'title' => 'E-mail', 'filters' => 'text'),
        'content' => array('tableAlias' => 'r', 'title' => 'Content', 'filters' => 'text'),
        'published' => array('tableAlias' => 'r', 'title' => 'Submited on', 'width' => 100, 'filters' => 'text'),
        'visible' => array('tableAlias' => 'r', 'title' => 'Visible', 'width' => 100, 'filters' => 'text'),
        'uri' => array('tableAlias' => 'r', 'title' => 'URL', 'width' => 100),
        'domain' => array('tableAlias' => 'r', 'title' => 'URL', 'width' => 100),
        'performer_id' => array('tableAlias' => 'r', 'title' => 'ssURL', 'width' => 100),
    );

    /**
     * Template Map
     * @var array
     */

    public function __construct()
    {
        return $this->config = array(
            'name' => 'Moderate Reviews',
            'showPagination' => true,
            'showQuickSearch' => false,
            'showItemPerPage' => true,
            'showColumnFilters' => true,
            'templateMap' => array(

                'paginator-slide' => __DIR__ . '/../../../view/templates/slide-paginator.phtml',
                'table-comments' => __DIR__ . '/../../../view/templates/comments-template.phtml',
                'default-params' => __DIR__ . '/../../../view/templates/default-params.phtml',
            ),

        );

    }

    public function init()
    {


    }

    //The filters could also be done with a parametrised query
    protected function initFilters($query)
    {
        $i = 1;
        if ($this->getParamAdapter()->getPureValueOfFilter('author')) {

            $username = $this->getParamAdapter()->getPureValueOfFilter('author');




            $query->andWhere($query->expr()->like('r.author', '?'.$i))->setParameter($i,'%'.$username.'%');
            $i++;
        }
        if ($this->getParamAdapter()->getPureValueOfFilter('contact')) {

            $value = $this->getParamAdapter()->getPureValueOfFilter('contact');


                $query->andWhere($query->expr()->like('r.contact', '?' . $i))->setParameter($i, '%' . $value . '%');
                $i++;


        }
        $valueActive = $this->getParamAdapter()->getPureValueOfFilter('visible');

        if (!is_null($valueActive)) {

            $value = $this->getParamAdapter()->getPureValueOfFilter('visible');

            if($value != 'any') {
                $query->andWhere('r.visible = ?'.$i)->setParameter($i,$value);
            }

        }




    }
}