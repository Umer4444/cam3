<?

class User_notifications_mail extends App_Model{
    
    protected $_name="user_notifications_mail";
    
    protected $_primary="id"; 
    
    public function getEmailPermission($user_type = null, $id_user = null, $notification_type = false){

      
        if(!$user_type || is_null($id_user) ) return false;
        
        $select = $this->select()->from(array("u" => $this->_name), array("id"));
        $select->where(" u.user_type='{$user_type}'");
        $select->where(" u.id_user='{$id_user}'");
        
        if($notification_type)
            $select->where(" u.notification_type='{$notification_type}'");
       
        return $this->fetchRow($select);
    }
    
    public function getPermissions($user_type = false, $id_user = fasle) {
        if(!$user_type || !$id_user)  return false;
 
        $select = $this->select()->from(array("u" => $this->_name), array("*"));
        $select->where(" u.user_type='{$user_type}'");
        $select->where(" u.id_user='{$id_user}'");
               
        return $this->fetchAll($select);
    }
    
    public function savePermissions($perms = array(), $user_type = false, $id_user = false){
                    
        if(!$user_type || !$id_user)  return false;

        $this->delete(" id_user=".(int)$id_user. " AND user_type='".$user_type."'");
        
        if(!empty($perms)){
            $statementInsert = "INSERT INTO user_notifications_mail (id_user, user_type, notification_type) VALUES ";
            foreach($perms as $p=>$v){
                $statementInsert .= "(".$id_user. ",'".$user_type."',". "'".$p."'),";
            }
            
            db()->query(trim($statementInsert,","));
        }
    }
}

