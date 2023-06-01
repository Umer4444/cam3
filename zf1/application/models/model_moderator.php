<?

class Model_moderator extends App_Model{
    
    protected $_name="model_moderator";

    protected $_primary="id"; 

/*    public function getField($name){
        
        $select = $this->select()
                       ->from($this->_name)
                       ->where("type=?", $name);
        
        return $this->fecthRow($select)->id;
        
    }
    public function getFieldbyId($id){
        
        $select = $this->select()
                       ->from($this->_name)
                       ->where("id=?", $id);
        
        return $this->fecthRow($select)->type;
        
    }*/
    public function getModelModerator($id_model = null){
        
        if(!$id_model) return null;
        
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array("mm" => "model_moderator"))->columns(array(""))
                        ->joinLeft(array("m"=>"moderator"), "m.id=mm.id_moderator", array("username" => "username", "screen_name" => "screen_name" ))
                        ->where("mm.id_model=?", $id_model)
                        ;  
        return $this->fetchRow($select);
    }
}