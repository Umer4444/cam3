<?

class Moderator_notes extends App_Model{
    
    protected $_name="moderator_notes";
    
    protected $_primary="id";
    
    function getNotesByModerator($id_moderator, $id_user = null, $type){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("n" => $this->_name), "*")
                       ->where("n.id_moderator=?", $id_moderator)
                       ->where("n.user_type=?", $type);
                       
        if(!is_null($id_user)) $select->where("n.id_user=?", $id_user);    
                  
        return $this->fetchAll($select);
    }
    
    public function setNotes($id_moderator, $id_user, $type, $notes){
        
        $_info = $this->getNotesByModerator($id_moderator, $id_user, $type);
        if($_info) $_info = $_info->toArray();
        else $_info = array();
        //p($_info);exit; 
        if (!$notes || $notes == ''){ //if the field is empty delete from db if it exists
            $this->delete("id_moderator=".$id_moderator." and id_user=".$id_user." and user_type='".$type."'");
            
        }
        else{
            if(count($_info) > 0){ //we have previous values 
                $this->update(array("notes" => $notes), "id_moderator=".$id_moderator." and id_user=".$id_user." and user_type='".$type."'");
            
            }else {//we insert new value
                $this->insert(array("notes" => $notes, "id_moderator" => $id_moderator, "id_user" => $id_user, "user_type" => $type, "date" => time()));
            }
        }
        return true;
    }
}