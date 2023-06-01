<?
class Pledge_perk extends App_Model{

    protected $_name="pledge_perk";
    protected $_primary="id";

  public function getById($id_perk = null,  $id_pledge = null){
      if(!$id_perk) return null;

      $select = $this->select()->where("id=?", (int)$id_perk);
      if($id_pledge){
          $select->where("id_pledge=?", (int)$id_pledge);
      }

      return $this->fetchRow($select);
  }

  public function getByIdPledge($id_pledge = null, $status = null, $user_type = "model"){
       if(!$id_pledge) return null;

       $select = $this->select()->setIntegrityCheck(false)
                                ->from(array("pp" => "pledge_perk"), array("*"))
                                ->joinLeft(
                                    array(
                                        "pf" => "funders"),
                                    "pp.id = pf.reference_id AND pf.entity = '".
                                    addslashes(Application\Entity\PledgeFunder::class)."'",
                                    array(
                                        "contributors" => new Zend_Db_Expr("count(pf.id)")
                                    )
                                )
                                ->where("pp.id_pledge=?", (int) $id_pledge)
                                ->group("pf.reference_id")
                                ->group("pp.id")

                                ->joinLeft(array("p" => "pledge"), "pp.id_pledge = p.id", array("start_date" => "start_date", "duration" => "duration", "duration_days" => "duration_days", "duration_type" => "duration_type"))
                                ->joinLeft(array("ph" => "photos"), "pp.id_photo = ph.id", array("filename" => new
                                Zend_Db_Expr("COALESCE(ph.filename, null)")))
                       ;

       if($status) $select->where("pp.status=?",$status);
       if($user_type) $select->where("pp.user_type=?",$user_type);

       return $this->fetchAll($select);

  }
  public function multipleDelete($notifications = null){
    if(!$notifications) return false;
    $notifications = trim($notifications, ",");
    $this->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '".$notifications."') > 0"));
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

}
