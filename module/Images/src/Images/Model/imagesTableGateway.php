<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License
 */
namespace Images\Model;

use Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;

class imagesTableGateway extends AbstractTableGateway
{
    protected $table = 'images';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new \Images\Model\imagesData);

        $this->initialize();
    }

    public function fetchAllSelect()
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(array('s' => 'images'));
        return $select;
    }

}
/* FOR ROI :   //'roi' => new \Zend\Db\Sql\Expression
('CONCAT(ROUND(((SUM(clicks)*a.price-(SUM(s.clicks)*a.cost))/(SUM(s.clicks)*a.cost)*100),0)," &#37;")'
),
*/