<?

class Payments extends App_Model{
    
    
    protected $_name="payments";
    
    protected $_primary="id";

    
    public function userChipsHistory($id_user = null)
    {
        if (!$id_user) {
            return false;
        } 
        
        if($id_user){
            
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from('payments',array('amount','chips','currency','added'))
                           ->joinLeft('payment_method','payments.payment_method=payment_method.id',array('name'))
                           ->where("payments.id_user=?",$id_user)
                           ->where("payments.status=1")
                           ->where("payments.payment_type='buy'")
                           ->order("payments.added");
            
            return $this->fetchAll($select)->toArray();           

        }

    }
}    