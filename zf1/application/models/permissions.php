<?

class Permissions extends App_Model{
    
    protected $_name="permissions";
    
    protected $_primary="id";
    
    public function getPermissionsForUser($user_id , $user_type){
        
        $select = $this->select()
                       ->from($this->_name)
                       ->where("id_user=?",$user_id)
                       ->where("type=?",$user_type);
        
        return $this->fetchAll($select);

    }
    
    public function deletePermissionsForUser($user_id , $user_type){
        
        db()->delete($this->_name, "id_user=".$user_id." and ".$this->getAdapter()->quoteInto("type=?",$user_type));

    }
    
    public function getPermission($user_id , $user_type, $action){
        
        $select = $this->select()
                       ->from($this->_name)
                       ->where("id_user=?",$user_id)
                       ->where("type=?",$user_type)
                       ->where("action=?",$action);
        
        return $this->fetchRow($select);

    }
    
    public function setPermission($user_id , $user_type, $action, $permission){
        
        $existing = $this->getPermission($user_id , $user_type, $action); 
        if($existing){
            if($existing->permission != $permission){
                $this->getAdapter()->update($this->_name, array("permission" => $permission), "id_user=".$user_id." and ".$this->getAdapter()->quoteInto("type=?",$user_type)." and ".$this->getAdapter()->quoteInto("action=?",$action));
            
            } 
        }else{
            $this->insert(array("id_user" => $user_id,
                                "type" => $user_type,
                                "action" => $action,
                                "permission" => $permission
                          ));
        } 
        
    }
    
    public function deletePermission($user_id , $user_type, $action){
        
        $this->getAdapter()->delete($this->_name, "id_user=".$user_id." and ".$this->getAdapter()->quoteInto("type=?",$user_type)." and ".$this->getAdapter()->quoteInto("action=?",$action));

    }
}