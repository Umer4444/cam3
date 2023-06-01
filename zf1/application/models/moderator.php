<?

use PerfectWeb\Core\Utils\Status;

class Moderator extends App_Model
{

    protected $_name = "moderator";

    protected $_primary = "id";

    protected $_rowClass = 'ModeratorRow';

    function checkUniqueEmail($val, $id_user = null, $ignore_logged = false)
    {
        $check = $this->select()->where("email=?", $val);
        if (!$ignore_logged && (Auth::isModerator() || $id_user)) $check->where("id<>?", ($id_user ? $id_user : (Auth::isModerator() ? $_SESSION['user']['id'] : $id_user)));

        return $this->fetchRow($check);

    }

    function checkUniqueUsername($val)
    {
        $check = $this->fetchRow($this->select()->where("username=?", $val));
        return $check;
    }


    /**
     * Approve/deny moderator
     *
     * @param mixed $id - id_user
     * @param mixed $active - true/false (approve/deny)
     */
    public function setActive($id, $active = null)
    {

        if (!is_null($active)) {

            if ($active) $status = 1;
            else $status = -1;
            $this->update(array("active" => $status), $this->getAdapter()->quoteInto("id=?", $id));
            return true;

        } else {
            return false;
        }
    }

    /**
     * @param mixed $active : 0 -> pendding / 1 -> active / -1 -> denied
     */
    public function getModerators($active = 1)
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->where("active=?", $active);

        return $this->fetchAll($select);
    }

    public function getModeratorsArray($active = 1)
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->where("active=?", $active);

        $results = $this->fetchAll($select);

        $return = array();
        foreach ($results as $r) {
            $return[$r->id] = $r->username;
        }

        return $return;
    }

    public function logout($id = null)
    {
        $this->update(array('online' => 0, 'session_id' => '-'), db()->quoteInto('id=?', $id));
        return true;
    }

    public function getNotificationEmail($id = null)
    {

        if (is_null($id)) return false;

        $row = $this->fetchRow($this->select(array("email", "notification_email"))->where("id=" . (int)$id));
        if ($row->notification_email)
            $email = $row->notification_email;
        else
            $email = $row->email;

        return $email;
    }

    public function nameArray()
    {
        $field = "username";
        $results = $this->fetchAll($this->select()->from($this->_name, array("id", $field)));
        $users = array();
        foreach ($results as $r) {
            $users[$r->id] = $r->$field;
        }

        return $users;
    }

    public function usernameSuggest($query = null)
    {
        if (!$query)
            return false;

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("id", "screen_name"))
            ->where("screen_name LIKE ?", $query . '% AND active = 1')
            ->limit(5);

        if ($_SESSION["group"] == "model") {
            $select->joinRight(array("me" => "messages"), "(m.id=me.id_sender AND me.sender_type = 'moderator') OR (m.id=me.id_receiver AND me.receiver_type = 'moderator')", array(""));
            $select->joinRight(array("mm" => "model_moderator"), "m.id=mm.id_model", array(""));
        }

        $result = $this->fetchAll($select);

        $arr = array();
        foreach ($result as $row) {
            $arr[] = array('id' => $row->id, 'name' => utf8_encode($row->username), "type" => "moderator");
        }
        return ($arr);
    }

    public function getNames()
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("id" => "id", "username" => "username", "screen_name" => "screen_name"))
            ->joinRight(array("p" => "photos"), "(m.id=p.user AND p.type = 'cover')", array("filename" => "filename"))
            ->where("m.active = ".Status::ACTIVE);


        $result = $this->fetchAll($select);

        $arr = array();
        foreach ($result as $row) {

            if ($row->filename) {
                $file_ = $row->filename;
            } else {
                $file_ = config()->{$default};
            }
            $arr[$row->id] = array(
                'name' => $row->screen_name,
                "picture" => $file_
            );
        }
        return ($arr);
    }
}

class ModeratorRow extends Zend_Db_Table_Row
{

    function getCover($all_data = null, $type = "cover", $dir = "photos", $default = "model_default_cover")
    {
        $moderator = new Moderator;
        $result = $moderator->fetchRow($moderator->select()
                ->setIntegrityCheck(false)
                ->from(array("p" => "photos"), "*")
                ->where("p.id_model=?", $this->id)
                ->where("p.type =?", $type)
                ->where("p.active =?", 1)
                ->order("p.id DESC")
        );

        if ($result->filename) {
            return $all_data ? $result : "/uploads/" . $dir . "/" . $result->filename;
        } else {
            return config()->{$default};
        }
    }
}