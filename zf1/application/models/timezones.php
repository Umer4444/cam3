<?

class Timezones extends App_Model{
    
    protected $_name="timezones";
    
    protected $_primary="id";
    
    public function getGMT($id) {          
        return $this->fetchRow($this->select()
                            //->from($this->_name,array("*"))
                            ->where("id=?", (int)$id)->limit(1)
                            );
    }
    
}