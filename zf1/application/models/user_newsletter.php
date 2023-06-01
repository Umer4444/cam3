<?

class User_newsletter extends App_Model{
    
    protected $_name="user_newsletter";
    
    protected $_primary="id";
    
    public function checkSubscription($id_user = false, $id_website = false) {
        if(!$id_website || !$id_user) return false;
                        
        $select = $this->select()
                        ->where("id_user=?", $id_user)
                        ->where("id_website=?", $id_website)
                        ->limit(1);
            
        if(count($this->fetchRow($select)) < 1){
            $this->insert(array(
                                "id_user"       => $id_user,
                                "id_website"    => $id_website,
                                "send"          => "1",
                            ));    
        }
        return;                                        
    }     
    public function getSubscription($id_user = false, $id_website = false) {
        if(!$id_website || !$id_user) return false;
                        
        $select = $this->select()
                        ->where("id_user=?", $id_user)
                        ->where("id_website=?", $id_website)
                        ->limit(1);
            

        return $this->fetchRow($select);                                        
    }     

}