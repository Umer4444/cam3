<?

class Webchat_history extends App_Model{
    
    protected $_name="webchat_history";
    
    protected $_primary="id";

    public function findById($id=null) {
        if(!$id) return false;
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from($this->_name)
            ->where("id=?", $id);

        return $this->fetchRow($select);
    }
    
}