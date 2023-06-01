<?

class User_access extends App_Model{
    
    protected $_name="user_access";
    
    protected $_primary="id";
    
    public function getUserAccess($id_user,$id_item,$item_type){
        
        $result = $this->fetchRow($this->select()->setIntegrityCheck(false)
                                 ->where("id_user=?", $id_user)
                                 ->where("id_item=?", $id_item)
                                 ->where("item_type=?", $item_type)
                                 );    
                                 
        if($result->id){    
            return 1;
        }else{
            return 0;
        }
    }
    
}