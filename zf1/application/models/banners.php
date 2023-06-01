<?

class Banners extends App_Model{

    protected $_name="banners";

    protected $_primary="id";

    public function getBannersModel($id_model = null, $status = null, $time = null){
        if (!$id_model) return false;

        $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->where("id_user=?",$id_model);
        if($status)
            $select->where("status=?", (int)$status);
        if($time) {
            $select->where("start_date <=?", time());
            $select->where("end_date >=?", time());
        }
//p($select."",1);
        return $this->fetchAll($select);
    }

    public function multipleAction($idList = null, $action = null){
        if(!$idList) return false;
        $idList = trim($idList, ",");
        switch ($action){
            case "deny":
                $this->update(array("status" => -1), new Zend_Db_Expr(" FIND_IN_SET(id, '".$idList."') > 0"));
                break;
            case "accept":
                $this->update(array("status" => 1), new Zend_Db_Expr(" FIND_IN_SET(id, '".$idList."') > 0"));
                break;
            case "delete":
                $this->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '".$idList."') > 0"));
                break;
            case "select":
                return $this->fetchAll($this->select()->where(new Zend_Db_Expr(" FIND_IN_SET(id, '".$idList."') > 0")));
                break;
        }

    }

    public function getById($id_banner = null)
    {
        if(!$id_banner) return false;
        $select = $this->select()->where(db()->quoteInto("id=?",(int)$id_banner));

        return $this->fetchRow($select);
    }
}