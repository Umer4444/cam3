<? class Chips_packages extends App_Model {

    protected $_name = "package";

    protected $_primary = "id";

    public function findById($id = null){
        if(!$id) return false;
        $select = $this->select()->where("id=?", $id);
        return $this->fetchRow($select);
    }

    public function fetchByAmount($amount){
        if(!$amount) return false;
        $select = $this->select()->where("amount<=?", $amount)->order("amount ASC");
        return $this->fetchAll($select);
    }
}