<?php

use PerfectWeb\Core\Utils\Status;

class User extends App_Model
{

    protected $_name = "user";

    protected $_primary = "id";

    protected $_rowClass = 'UserRow';

    function checkUniqueEmail($val, $id_user = null)
    {
        $check = $this->select()->where("email=?", $val);
        if (Auth::isUser() || $id_user) $check->where("id<>?", Auth::isUser() ? $_SESSION['user']['id'] : $id_user);
        return $this->fetchRow($check);

    }

    function checkUniqueUsername($val)
    {
        $check = $this->fetchRow($this->select()->where("username=?", $val));
        return $check;
    }

    function checkNickName($val, $id_user = null)
    {
        $check = $this->select()->where("display_name=?", $val);
        if (Auth::isUser() || $id_user) $check->where("id!=?", Auth::isUser() ? $_SESSION['user']['id'] : $id_user);
        return $this->fetchRow($check);
    }

    /**
     * Approve/deny user
     *
     * @param mixed $id - id_user
     * @param mixed $active - true/false (approve/deny)
     */
    public function setActive($id, $active = null)
    {

        if (!is_null($active)) {

            if ($active) $status = 1;
            else $status = -1;
            $this->update(array("state" => $status), $this->getAdapter()->quoteInto("id=?", $id));
            return true;

        } else {
            return false;
        }
    }


    /**
     * @param mixed $active : 0 -> pendding / 1 -> active / -1 -> denied
     */
    public function getUsers($active = 1)
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->where("state=?", $active);

        return $this->fetchAll($select);
    }

    public function getUserById($id_user = null)
    {
        $select = $this->select()->setIntegrityCheck(false)
            ->where("id=?", (int)$id_user)
            ->where("state=1");


        return $this->fetchRow($select);
    }

    public function getUserByName($name)
    {
        return $this->fetchRow($this->select()->where("username=?", $name)->where("state=1"));
    }

    public function searchUserByName($name)
    {
        $result = $this->fetchAll($this->select()->where("username LIKE ?", "%" . $name . "%")->where("state=1"));
        return $result;
    }

    public function logout($id = null)
    {
        $this->update(array('online' => 0, 'session_id' => '-'), db()->quoteInto('id=?', $id));
        return true;
    }

    public function usernameAutocomplete($query = null)
    {
        if (!$query)
            return false;

        $resultSet = $this->select()
            ->where("username LIKE ?", '%' . $query . '%')
            ->limit(5);

        $result = $this->fetchAll($resultSet);

        $arr = array();
        foreach ($result as $row) {
            $arr[] = array('id' => $row->user_id, 'name' => utf8_encode($row->username));
        }
        return json_encode($arr);
    }

    public function nameArray()
    {
        $field = "username";
        $results = $this->fetchAll($this->select()->from($this->_name, array("id", $field)));
        $users = array();
        foreach ($results as $r) {
            $users[$r->user_id] = $r->$field;
        }

        return $users;
    }

    public function verifyEmail($code = null)
    {

        if (!$code) return "No code provided";

        $u = $this->fetchRow($this->select()->from($this->_name, array("id", "activation_code", "username"))->where("activation_code =?", $code)->limit(1));
        if ($u) {
            if ($this->update(array("state" => 1, "activation_code" => null), $this->getAdapter()->quoteInto("activation_code =?", $code)))
                return array("message" => "Account activated. You can login", "user" => $u->id, "name" => $u->username, "status" => "success");
            else
                return array("message" => "Error while activation. Try again later", "status" => "fail");
        } else {
            return array("message" => "Invalid code", "status" => "fail");
        }
    }

    public function usernameSuggest($query = null)
    {
        if (!$query)
            return false;

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("id", "username"))
            ->where("username LIKE ?", $query . '%')
            ->limit(5);

        if ($_SESSION["group"] == "model") {
            $select->joinRight(array("me" => "messages"), "(m.id=me.id_sender AND me.sender_type = 'model') OR (m.id=me.id_receiver AND me.receiver_type = 'model')", array(""));
            $select->joinRight(array("uf" => "user_favorites"), "m.id=uf.id_model", array(""));
        }

        $result = $this->fetchAll($select);

        $arr = array();
        foreach ($result as $row) {
            $arr[] = array('id' => $row->user_id, 'name' => utf8_encode($row->username), "type" => "user");
        }
        return ($arr);

    }

    public function getNames()
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("id", "username", "avatar"))
            ->joinRight(array("p" => "photos"), "(m.id=p.user AND p.type = 'cover')", array("filename" => "filename"))
            ->where("m.state = ".Status::ACTIVE);

        $result = $this->fetchAll($select);

        $arr = array();
        foreach ($result as $row) {

            if ($row->filename) {
                $file_ = $row->filename;
            } else {
                if (!isset($default)) $default = '';
                $file_ = config()->{$default};
            }
            $arr[$row->user_id] = array(
                'name' => $row->username ? $row->username : "anonymous",
                "picture" => $file_
            );
        }
    }

    /**
     * returns array with id user as key and user chips as value
     * @param null $string
     * @param bool $status
     * @return array
     */
    public function getUsersChipsByIdSet($string = null, $status = true)
    {
        if (!$string) return false;

        $select = $this->select()
            ->from($this->_name, array("id" => "id", "chips" => "chips"))
            ->where(new Zend_Db_Expr("FIND_IN_SET(id,'" . trim($string, ",") . "')"));
        if ($status)
            $select->where("state=1");

        $users = $this->fetchAll($select);
        $arr = array();

        foreach ($users as $user)
            $arr[$user->id] = $user->chips;

        return $arr;

    }

    /**
     * update users chips
     *
     */
    public function updateChips($value = null, $id_user = null)
    {
        if (!$value || !$id_user) return false;
        $this->update(array("chips" => new Zend_Db_Expr("chips-" . (int)$value)), "id=" . (int)$id_user);
    }
}

class UserRow extends Zend_Db_Table_Row
{

    function getAvatar()
    {
        $user = new User;
        if (!empty($this->avatar)) {
            return "/uploads/user/" . $this->avatar;
        } else {
            return config()->user_default_avatar;
        }
    }

    function getUserCountry()
    {
        $user = new User;
        if ($this->country) {
            $result = $user->fetchRow($user->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c" => "countries"), "*")
                    ->where("c.id=?", $this->country)
            );
            return $result;
        } else return array('id' => 0, "code" => "--", 'name' => 'None');
    }

    function getChatStyle(){
        $user = new User;
        $sql = "SELECT rv.value as chat_font from user_resource as r LEFT JOIN user_resource_value as rv on r.id = rv.resource_id WHERE r.setting_key = 'chat_font' AND rv.user_id = ".$this->id ." LIMIT 1";

        $result = $user->fetchRow(
          $user->select()
            ->setIntegrityCheck(false)
            ->from(array('r' => "user_resource"), '')
            ->joinLeft(array('rv' => "user_resource_value"),"r.id = rv.resource_id", array('value' => "chat_font"))
        );

        return $result;
    }
    function getStream($id_user = null)
    {
        return md5("user" . is_null($id_user) ? $this->user_id : $id_user);
    }
}
