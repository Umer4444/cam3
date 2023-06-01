<?

class User_settings extends App_Model{
    
    protected $_name="user_settings";
    
    protected $_primary="id_user"; //dummy
    
    public function getFieldsByUser($id_user = null, $user_type = null, $setting_type = null){

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("s" => $setting_type), array("type", "id"))
                       ->joinLeft(array("m" => $this->_name),"s.id=m.id_field".($id_user ? " AND m.id_user=".$id_user : "").($user_type ? " AND m.user_type='".$user_type."'" : "").($setting_type ? " AND m.setting_type='".$setting_type."'" : "") , array("value"));


        if(is_null($id_user)) $select->group("m.id_user");
        
        return $this->fetchAll($select);                         
    }

    function getUserFieldById($id_user = null, $user_type = null){
            
            if(!$id_user || !$user_type) return false;
            
            $results = $this->fetchAll($this->select()
                                    ->from($this->_name, array("setting_type", "value"))
                                    ->where("id_user=?", $id_user)
                                    ->where("user_type=?", $user_type)
                                    
                               );
            $r = array();
           foreach($results as $result){
               $r[$result->setting_type] = $result->value;
           } 
           
           return $r;
    }
        
    function getUserFieldByIdArray($id_user = null, $user_type = null, $key = null, $setting = null){
            
            if(!$id_user || !$user_type || !$key || !$setting) return false;
            
            $results = $this->fetchAll($this->select()
                                    ->from($this->_name, array("setting_type", "value", "id_field"))
                                    ->where("id_user=?", $id_user)
                                    ->where("user_type=?", $user_type)
                                    ->where("id_field=?", $key)
                                    ->where("setting_type=?", $setting)
                                    
                               );
            $r = array();
            p($results."",1);
           foreach($results as $result){
               $r[$result->setting_type] = $result->value;
               
           } 
           
           return $r;
    }
  
      function getSettingsByUserId($id_user, $id_field, $user_type, $setting_type){
        return $this->fetchRow($this->select()
                                    ->where("id_user=?", $id_user)
                                    ->where("id_field=?", $id_field)
                                    ->where("user_type=?", $user_type)
                                    ->where("setting_type=?", $setting_type)
                                    
                               );
    }
      
    public function deleteFieldsByUser($id_user, $user_type){
        
        db()->delete($this->_name, $this->getAdapter()->quoteInto("id_user=?",$id_user)." and ".$this->getAdapter()->quoteInto("user_type=?",$user_type));
                         
    }
}