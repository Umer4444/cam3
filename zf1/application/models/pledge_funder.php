<?php
class Pledge_funder extends App_Model{

    protected $_name="funders";
    protected $_primary="id";

    public function getFunders($id = null) {
        if(!$id) return null;
        $select = $this->select()->from(array("pf" => $this->_name))->setIntegrityCheck(false)
                    ->joinLeft(
                                array("u" => "user"),
                                "pf.id=u.id",
                                array(
                                    "username" => "COALESCE(u.username, null)",
                                    "avatar" => "COALESCE(CONCAT('/uploads/photos/',u.avatar), '". config()->model_default_photo_id."')",

                                    ));

        $select->where("pf.reference_id=?", (int)$id);
        $select->where("pf.anonymous=0");
        $select->where("pf.entity = ?", Application\Entity\PledgeFunder::class);
        $select->group("pf.id");
        $select->limit(40);
        return $this->fetchAll($select);
    }

    public function getLargestContributor($id=null) {
        if(!$id) return null;
        $select = $this->select()->from(
                                            array("pf" => $this->_name),
                                            array("total" => new Zend_Db_Expr("SUM(pf.amount)"), "*")
                                        )
                                        ->setIntegrityCheck(false)
                    ->joinLeft(
                                array("u" => "user"),
                                "pf.id=u.id",
                                array(
                                    "username" => "COALESCE(u.username, null)",
                                    "avatar" => "COALESCE(CONCAT('/uploads/photos/',u.avatar), '". config()->model_default_photo_id."')",

                                    ));
        $select->where("pf.reference_id = ?", (int)$id);
        $select->where("pf.entity = ?", Application\Entity\PledgeFunder::class);
        $select->group("pf.id");
        $select->order("total desc");
        $select->limit(1);
        return $this->fetchRow($select);
    }

    public function getLastContributor($id = null){

        if(!$id) return null;

        $select = $this->select()->from(array("pf" => $this->_name), array("id"=>"id", "id" => "id"))->setIntegrityCheck(false)
                    ->joinLeft(
                                array("u" => "user"),
                                "pf.id=u.id",
                                array(
                                    "username" => "COALESCE(u.username, null)",
                                    "avatar" => "COALESCE(CONCAT('/uploads/photos/',u.avatar), '". config()->model_default_photo_id."')",

                                    ));
        $select->where("pf.reference_id = ?", (int)$id);
        $select->where("pf.entity = ?", Application\Entity\PledgeFunder::class);
        $select->order("id desc");
        $select->limit(1);
        return $this->fetchRow($select);

    }

}