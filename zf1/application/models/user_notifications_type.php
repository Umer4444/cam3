<?

class User_notifications_type extends App_Model{
    
    protected $_name="user_notifications_type";
    
    protected $_primary="id"; 
    
    public function getAll(){
        return $this->fetchAll($this->select());
    }
}

