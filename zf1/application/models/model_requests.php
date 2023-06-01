<?
class Model_requests extends App_Model{

    protected $_name="model_requests";

    protected $_primary="id_model"; //dummy

    public function addRequest($model_id, $user_id, $type, $status, $memberCam = 0){
        $this->getAdapter()->query("insert into ".$this->_name." set ".$this->getAdapter()->quoteInto("id_model=?", $model_id)." ,
                                           ".$this->getAdapter()->quoteInto("id_user=?", $user_id)." ,
                                           ".$this->getAdapter()->quoteInto("status=?", $status)." ,
                                           ".$this->getAdapter()->quoteInto("member_camera=?", $memberCam)." ,
                                           ".$this->getAdapter()->quoteInto("type=?", $type)
                                   );

        return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("status=?", $status)."
                                           and ".$this->getAdapter()->quoteInto("member_camera=?", $memberCam)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   )->fetchAll();
    }

    public function deleteRequest($model_id, $user_id, $type){
        $this->getAdapter()->query("delete from ".$this->_name." where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   );

        return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   )->fetchAll();
    }

    public function updateRequest($model_id, $user_id, $type, $status){
        //update status
        $this->getAdapter()->query("update ".$this->_name." set ".$this->getAdapter()->quoteInto("status=?", $status).", viewed=1 where
                                           ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   );

        //delete all other requests from this user
        $this->getAdapter()->query("delete from ".$this->_name." where NOT (".$this->getAdapter()->quoteInto("status=?", $status)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type).") and
                                           ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id).""

                                   );

        //deny other pending requests for the same type
        $this->getAdapter()->query("update ".$this->_name." set status='denied' where
                                           ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user!=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   );
        //return modified entry
        return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("status=?", $status)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   )->fetchAll();
    }

    public function updateGroupRequest($model_id, $type, $status, $user_id = null){
        //update status
        $this->getAdapter()->query("update ".$this->_name." set ".$this->getAdapter()->quoteInto("status=?", $status).", viewed=1 where
                                           ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type).
                                           ($user_id ? " and ".$this->getAdapter()->quoteInto("id_user=?", $user_id) : "")
                                   );

        //delete all other requests from this model(except group)
        $this->getAdapter()->query("delete from ".$this->_name." where ".$this->getAdapter()->quoteInto("type<>?", $type)." and
                                           ".$this->getAdapter()->quoteInto("id_model=?", $model_id)

                                   );
         if(!is_null($user_id)){
            //return modified entry
            return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                               where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                               and ".$this->getAdapter()->quoteInto("status=?", $status)."
                                               and ".$this->getAdapter()->quoteInto("type=?", $type).
                                               " and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)
                                       )->fetchAll();

        }
    }

    public function getGroupRequest($model_id, $id_user, $type, $status){

        //return modified entry
        return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("status=?", $status)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $id_user)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   )->fetchAll();
    }

    public function denyRequest($model_id, $user_id, $type){
        //update status
        $this->getAdapter()->query("update ".$this->_name." set status='denied' where
                                           ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   );

        //return modified entry
        return $this->getAdapter()->query("select count(*) as count, ".$this->_name.".* from ".$this->_name."
                                           where ".$this->getAdapter()->quoteInto("id_model=?", $model_id)."
                                           and ".$this->getAdapter()->quoteInto("id_user=?", $user_id)."
                                           and ".$this->getAdapter()->quoteInto("type=?", $type)
                                   )->fetchAll();
    }

    public function getRequests($model_id, $user_id = null, $type = null){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("m" => $this->_name), array('count(*) as count', 'm.id_model', 'm.id_user', 'm.type', 'm.status', 'm.member_camera'))
                       ->from(array('u' => 'user'), array('u.display_name'))
                       ->where("m.id_user=u.id")
                       ->where("id_model=?", $model_id)
                       ->where(new Zend_Db_Expr("((viewed<>1 AND status='pending') OR status<>'pending')"));

        if(!is_null($user_id)) $select->where("id_user=?", $user_id);
        if(!is_null($type)) $select->where("type=?", $type);
        $response = $this->fetchAll($select);

        if ($response) return $response;
        else return false;

    }
}