<?

class Payments_info extends App_Model{
    
    protected $_name="payments_info";
    
    protected $_primary="id"; 
    
    public function getPaymentsInfoByUserId($id_user,$user_type){
        $result = $this->fetchAll($this->select()->setIntegrityCheck(false)                               
                                                 ->where("id_user=?", $id_user)
                                                 ->where("user_type=?", $user_type)
                                      
                                  );
        return $result;
    }
    

     public function getPaymentsMethodsByUserId($id_user,$user_type){

        $result = $this->fetchAll($this->select()->setIntegrityCheck(false)
                                                 ->from(array("pi" => $this->_name), array("info"))       
                                                 ->where("pi.id_user=?", $id_user)
                                                 ->where("pi.user_type=?", $user_type)
                                                 ->from(array("pm" => "payment_method"), array("name"))
                                                 ->where("pi.payment_method=pm.id")
                                  );
        return $result;
    }   
    /**
    * 
    * @param int $id_user
    * @param int $user_type
    * @param int $payment_method 
    * @param array $info
    */
    public function updatePaymentInfo($id_user,$user_type,$payment_method,$info){
        
        $result = $this->fetchRow($this->select()->setIntegrityCheck(false)
                                                 ->where("id_user=?", $id_user)
                                                 ->where("user_type=?", $user_type)
                                                 ->where("payment_method=?", $payment_method)
                                          );
        
        if($result->id){
            //update
            $this->update(array("info" => serialize($info)), 
                                       db()->quoteInto("id_user=?", $id_user)." and ".
                                       db()->quoteInto("user_type=?", $user_type)." and ".
                                       db()->quoteInto("payment_method=?", $payment_method)
                                       );
        }else{
            //insert
            $this->insert(array("id_user" => $id_user,
                                "user_type" => $user_type,
                                "payment_method" => $payment_method,
                                "info" => serialize($info)
                                ));
        }                                  
        
    }
    
    
    
    
    
}    