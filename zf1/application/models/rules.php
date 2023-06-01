<?

class Rules extends App_Model{
    
    protected $_name = "rules";
    
    protected $_primary = "id"; 

    public function getRules($type = null){
        
        $select = $this->select()
                       ->from(array("r" => $this->_name), "*")
                       ;
        if($type) $select->where("r.type=?", $type);
        
        return $this->fetchAll($select);                     
    }
    
    public function getRule($id){
        
        $select = $this->select()
                       ->from(array("r" => $this->_name), "*")
                       ->where("r.id=?", $id);
        
        return $this->fetchRow($select);                     
    }
    
    public function saveRule($id, $data){
        
        return $this->update($data, $this->getAdapter()->quoteInto("id=?", $id));
                    
    }
    
    public function addRule($data){
        
        return $this->getAdapter()->insert($this->_name, $data);
                    
    }
    
    public function deleteRule($id){
        
        return $this->getAdapter()->delete($this->_name, $this->getAdapter()->quoteInto("id=?", $id));
                             
    }
    
}    