<?php
class Templates extends App_model {
    
    protected $_name="templates";

    protected $_primary="id"; 
    
    
    public function getContent($name = null){
        if(!$name) return false;
        $select = $this->select()
                       ->from($this->_name, array("*"))
                       ->where("name=?", $name);
        return $this->fetchRow($select);                 
    }
    
   
}