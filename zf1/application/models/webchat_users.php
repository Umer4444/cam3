<?

class Webchat_users extends App_Model
{

    protected $_name = "webchat_users";

    protected $_primary = "id";

    /**
     * Returns active chat user/model
     *
     * @param mixed $id_model
     * @param mixed $id_user - for model: 'model_x' ; for user: 'user_x' or 'guest'
     */
    function getUser($id_model, $id_user)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), "*")
            ->where("u.id_model=?", $id_model)
            ->where("u.id_user=?", $id_user);

        return $this->fetchRow($select);
    }

    function hasUser($id_model, $type)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), "*")
            ->where("u.id_model=?", $id_model)
            ->where("substr(u.id_user,1,4)!='mode'");
            if($type) {
                $select->where("u.chat_type=?", $type);
            }
        return $this->fetchRow($select);
    }

    function getLogged($id_model, $id_user)
    {

        if (!$id_user) return false;

        $id_user = 'user_' . $id_user;

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), "loggedin")
            ->where("u.id_model=?", $id_model)
            ->where(db()->quoteInto("u.id_user=?", $id_user));

        return $this->fetchRow($select);
    }

    function updateLogged($id_model, $id_user, $broadcast = false)
    {

        if (!$id_user) return false;
        if (!$id_model) return false;

        $id_user = 'user_' . $id_user;

        $where[] = db()->quoteInto("id_model=?", $id_model);
        $where[] = db()->quoteInto("id_user=?", $id_user);
        $values = array(
            "loggedin" => time(),
            "broadcast_mode" => "",
            "quality" => ""
        );
        if ($broadcast) {
            $values["broadcast_mode"] = "duplex";
            $values["quality"] = "sd";
        }

        $update = db()->update($this->_name, $values, $where);

        return true;
    }

    function hasModel($id_model)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), "count(*) as count")
            ->where("u.id_model=?", $id_model)
            ->where("u.id_user='model_?'", $id_model);

        return $this->fetchRow($select)->count;
    }

    function getPrivateUserId($id_model)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), "id_user")
            ->where("u.id_model=?", $id_model)
            ->where("u.id_user!=?", "model_" . $id_model)
            ->where("u.chat_type = 'private'");
        $user = $this->fetchRow($select)->id_user;
        $id = str_replace("user_", "", $user);

        return (int)$id;
    }

    function getPrivateUserSession($id_model)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), array("id_user", "broadcast_mode", "id_model", "quality"))
            ->where("u.id_model=?", $id_model)
            ->where("u.id_user!=?", "model_" . $id_model)//->where("u.chat_type='private'")
        ;
        return $this->fetchRow($select);


        //return (int)$id;
    }

    function updateUserChat($id_user = null, $user_type = null, $type = null)
    {
        if (!$id_user || !$user_type || !$type) return false;
        $this->update(array("chat_type" => $type), db()->quoteInto("id_user=?", $user_type . "_" . $id_user));
    }

    public function updateBroadcastMode($id_model, $id_user, $mode, $quality)
    {

        if (!$id_user || !$id_model || !$mode) return false;

        $where = db()->quoteInto(" id_model=?", $id_model);
        $where .= db()->quoteInto(" AND id_user=?", $id_user);
        $this->update(array("broadcast_mode" => $mode, "quality" => $quality), $where);
    }

    public function countUsers($id = null, $type = null)
    {
        if (!$id) return false;
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => $this->_name), array("counter" => new Zend_Db_Expr('count(id)')))
            ->where("u.id_model=?", $id);
        if ($type)
            $select->where("u.chat_type=?", $type);
        $result = $this->fetchRow($select);

        return ($result->counter ? $result->counter : "0");
    }

}