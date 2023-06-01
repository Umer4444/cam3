<?

class Rates_limits extends App_Model{
    
    protected $_name="rates_limits";
    
    protected $_primary="id";
    
    public function getLimitsByType($type = null){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("m" => $this->_name), array("value", "limit_type"))
                       ->from(array("r" => "rates"), array("type", "id"))
                       ->where("r.id=m.id_rate");
                       
        if(!is_null($type)) $select->where("m.limit_type=?", $type);
                  
        return $this->fetchAll($select);
                         
    }
    function getLimit($id_rate, $type){
        return $this->fetchRow($this->select()->where("id_rate=?", $id_rate)->where("limit_type=?", $type));
    }
    
}