<? 
class Pledge_update extends App_Model{
    
    protected $_name="pledge_update";    
    protected $_primary="id";

  public function getByIdPledge($id_pledge = null){
       if(!$id_pledge) return null;
       
       $select = $this->select()->setIntegrityCheck(false)
                                ->from(array("pu" => "pledge_update"), array("*"))
                                
                                ->where("pu.id_pledge=?", (int) $id_pledge)
                                ->order("added DESC")
                                
                       ;

       return $this->fetchAll($select);
       
  }
     
}     
?>