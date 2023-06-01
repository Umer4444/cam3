<?php

namespace Application\Table;

use ZfTable\AbstractTable;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ReviewsTable
 * @package Application\Table
 */
class ReviewsTable extends AbstractTable
{

    protected $config;

    //Definition of headers
    protected $headers = array(
        'id' => array('tableAlias' => 'r', 'title' => 'Id', 'width' => '15'),
        'date' => array('tableAlias' => 'r', 'title' => 'Date', 'sortable' => true),
        'review' => array('tableAlias' => 'r', 'title' => 'Review'),
        'user' => array('tableAlias' => 'r', 'title' => 'Reviewer', 'filters' => 'text'),
        'resourceType' => array('tableAlias' => 'r', 'title' => 'Resource Type', 'filters' => 'text'),
        'active' => array('tableAlias' => 'r', 'title' => 'Action', 'width' => 100, 'filters' => 'text'),
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
                'table-custom' => __DIR__ . '/../../../view/templates/custom-template.phtml',
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
        if ($this->getParamAdapter()->getPureValueOfFilter('user')) {

            $username = $this->getParamAdapter()->getPureValueOfFilter('user');

            $value = $query->getEntityManager()->getRepository('Application\Entity\User')->findOneBy(array('username' => $username))->getId();


            $query->andWhere($query->expr()->like('u.id', '?'.$i))->setParameter($i,$value);
            $i++;
        }
        if ($this->getParamAdapter()->getPureValueOfFilter('resourceType')) {

            $value = $this->getParamAdapter()->getPureValueOfFilter('resourceType');
            if($value != 'any') {

                $query->andWhere($query->expr()->like('r.resourceType', '?' . $i))->setParameter($i, '%' . $value . '%');
                $i++;
            }

        }
        $valueActive = $this->getParamAdapter()->getPureValueOfFilter('active');

        if (!is_null($valueActive)) {

            $value = $this->getParamAdapter()->getPureValueOfFilter('active');

            if($value != 'any') {
                $query->andWhere('r.active = ?'.$i)->setParameter($i,$value);
            }

        }




    }
}