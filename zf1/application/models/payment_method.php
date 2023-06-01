<?

class Payment_method extends App_Model{

    protected $_name="payment_method";

    protected $_primary="id";

    function getPaymentMethods($status = 1){
        $select = $this->select()
                       ->setIntegrityCheck(false);

        if($status) $select->where("status=?", $status);

        return $this->fetchAll($select);
    }

    function getMethodByType($type){
        $select = $this->select()->where("UCASE(name)=?", strtoupper($type));
        return $this->fetchRow($select)->id;
    }

}