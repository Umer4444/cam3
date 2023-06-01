<?

class Reviews extends App_Model{
    
    protected $_name="reviews";
    
    protected $_primary="id";

    /**
     * @param bool $resource_id
     * @param bool $resource_type
     * @param bool $active
     * @return bool|Zend_Db_Table_Rowset_Abstract
     */
    public function getReviews($resource_id = false, $resource_type = false, $active = false) {
        
        if(!$resource_id || !$resource_type) return false;
        $select = $this->select()
                        
                        ->where("resource_id=".(int)$resource_id)
                        ->where("resource_type ='".$resource_type."'");
        if($active) $select->where("active=1");
        
        return $this->fetchAll($select);
    }

    /**
     * @param $id
     * @param $type
     * @param $resource_id
     * @return bool|Zend_Db_Table_Rowset_Abstract
     */
    public function getPendingByUserId($id, $type, $resource_id) {

        if (is_null($id) || !$type) return false;
        $select = $this->select()
            ->where("user = ". (int)$id)
            ->where("resource_id=" . (int)$resource_id)
            ->where("active = 0")
            ->where("resource_type ='" . $type . "'");

        return $this->fetchAll($select);


    }

}    