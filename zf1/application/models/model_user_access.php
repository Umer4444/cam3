<?

class Model_user_access extends App_Model{

    protected $_name="model_user_access";

    protected $_primary="id";

    public function getFieldsByModel($id_model = null, $id_user = null ,$only_saved = null){

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("s" => "model_access"), array("type"))
                       ;

        if(is_null($id_user) || $only_saved == true){
            $select->from(array("m" => $this->_name) , array("*"))
                   ->where("s.id=m.action".($id_model ? " AND m.id_model=".$id_model : "").($id_user ? " AND m.id_user=".$id_user : ""))
                   ->from(array("u" => "user"), array("username"))
                   ->where("u.id=m.id_user")
                   ->order(array("m.id_user", "m.action"));
        }else{
            $select->joinLeft(array("m" => $this->_name),"s.id=m.action".($id_model ? " AND m.id_model=".$id_model : "").($id_user ? " AND m.id_user=".$id_user : "") , array("*"));
        }
        return $this->fetchAll($select);

    }

    public function getRestrictedWeb($id_model = null, $id_user = null, $ip = null){

        if(!$ip) $ip = $_SERVER["REMOTE_ADDR"];

        if(!$id_model) return null;

        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array("mu" => $this->_name), array("*", "user_ban" => "id_user"))
                        ->joinLeft(array("ma" => "model_access"), "mu.action=ma.id")
                        ->where("mu.id_model=?", $id_model);
          if($id_user)
                $select->where("mu.id_user=?", str_replace("user_", "", $id_user));
          else
                $select->where("mu.ip=?", $ip);

                $select->where("ma.type=?", "accessing_my_webcam") // restricted webchat
                       ->where("mu.to >=?", time())
                       ;


        $results = $this->fetchAll($select);
        $restricted = array("ip" => array(), "user" => array());
        foreach($results as $result){
            $restricted['ip'][] = $result["ip"];
            $restricted['user'][] = "user_".$result["user_ban"];
        }

        return $restricted;
    }

    function getUserFieldById($id_model, $id_user, $id_field, $user_type, $setting){
        return $this->fetchRow($this->select()
                                    ->where("id_user=?", $id_user)
                                    ->where("id_model=?", $id_model)
                                    ->where("action=?", $id_field)
                                    ->where("setting=?", $setting)

                               );
    }

    public function deleteFieldsByUser($id_model, $id_user){

        db()->delete($this->_name, $this->getAdapter()->quoteInto("id_user=?",$id_user)." and ".$this->getAdapter()->quoteInto("id_model=?",$id_model));

    }
}