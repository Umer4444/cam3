<?

class Special_requests extends App_Model{
    
    protected $_name="special_requests";

    protected $_primary="id"; 

    /**
    * status:
    *   0 - user to model new special request
    *   1 - model conter offer
    *   2 - special request completed and closed
    *  -1 - special request denied and closed
    */
    
    public function getItems(){
        return array('picture'=>'Picture','video'=>'Video','article of clothing'=>'Article of clothing','used item'=>'Used item','other'=>'Other');
    }


    public function getRequests($id_user = null,$id_model = null, $status = null,$nr=30,$limit=0){
        
        if(!$id_user && !$id_model) return false;
      
        $select = $this->select()->setIntegrityCheck(false)
                                    ->from(array("r"=>"special_requests"),"*")
                                    ->from(array("u"=>"user"),array("username","screen_name"))
                                    ->where("m.status=1")
                                    ->where("u.state=1")
                                    ->where("u.id=r.id_user")
                                    ->where("m.id=r.id_model")
                                    ->order("r.last_update DESC") 
                                    ->group("r.id") 
                                    ->limit($nr, $limit);
                        
        if($id_user) $select->where("r.id_user=?",$id_user);                    
        if($id_model) $select->where("r.id_model=?",$id_model);                    
        if($status) $select->where("r.status=?",$status);
                                       
        return $this->fetchAll($select);
    }
    
    public function getSpecialRequestById($id_special_request){
        
        if(!$id_special_request) return false;
      
        $select = $this->select()->setIntegrityCheck(false)
                                    ->from(array("r"=>"special_requests"),"*")
                                    ->from(array("m"=>"model"),array("screen_name"))
                                    ->from(array("u"=>"user"),array("username"))
                                    ->where("r.id=?",$id_special_request)
                                    ->where("m.active=1")
                                    ->where("u.active=1")
                                    ->where("u.id=r.id_user")
                                    ->where("m.id=r.id_model")
                                    ->order("r.last_update DESC") 
                                    ->group("r.id");
                        
        return $this->fetchRow($select);
    }
       
}