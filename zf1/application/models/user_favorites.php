<?

class User_favorites extends App_Model{
    
    protected $_name="user_favorites";
    
    protected $_primary="id_user"; //dummy
    
    public function addFavorite($model_id, $user_id){
        $this->getAdapter()->query("insert into ".$this->_name." set ".$this->getAdapter()->quoteInto("id_model=?", $model_id)." ,
                                           ".$this->getAdapter()->quoteInto("id_user=?", $user_id)
                                   );
 
        return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)." 
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)
                                   )->fetchAll();
    }
    
    public function removeFavorite($model_id, $user_id){
        $this->getAdapter()->query("delete from ".$this->_name." where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)." 
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)
                                   );
 
        return $this->getAdapter()->query("select count(*) as count from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)." 
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)
                                   )->fetchAll();
    }
    
    public function getFriends($id_model = null)  {
        if(!$id_model) return false;
        
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array("uf" => "user_favorites"), array("id_user"))
                        ->joinLeft(array("u" => "user"), "uf.id_user=u.id", array("username" => "username", "avatar" => "avatar"))
                        ->where("uf.id_model=".(int)$id_model)
                        ->where("u.state=1")
                        ->order("id DESC")
                        ->limit(12)
                        ;
        return $this->fetchAll($select);
                        
    }

}