<?

class Messages extends App_Model
{

    protected $_name = "messages";
    protected $_primary = "id";

    public function getUserOutbox($id_user, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"), array(
                //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                "id as mes_id",
                "subject",
                "message",
                "send_date",
                "read",
                "tip",
                "user_type" => "receiver_type",
                "user_id" => "id_receiver"))
            ->where("mes.id_sender=?", $id_user)
            ->where("mes.sender_type='user'")
            //->where("mes.id_receiver=m.id")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getUserInbox($id_user, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"), array(
                //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                "id as mes_id",
                "subject",
                "message",
                "send_date",
                "read",
                "tip",
                "user_type" => "sender_type",
                "user_id" => "id_sender"))
            ->where("mes.id_receiver=?", $id_user)
            ->where("mes.receiver_type='user'")
            ->where("mes.type='inbox'")
            //->where("mes.id_sender=m.id")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getUserArchive($id_user, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"), array(
                //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                "id as mes_id",
                "subject",
                "message",
                "send_date",
                "read",
                "tip",
                "user_type" => "sender_type",
                "user_id" => "id_sender"))
            ->where("mes.id_receiver=?", $id_user)
            ->where("mes.receiver_type='user'")
            ->where("mes.type='archive'")
            //->where("mes.id_sender=m.id")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getModelOutbox($id_model, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array(
                    //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                    "id as mes_id",
                    "subject",
                    "message",
                    "send_date",
                    "read",
                    "tip",
                    "user_type" => "receiver_type",
                    "user_id" => "id_receiver"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_sender=?", $id_model)
            ->where("mes.sender_type='model'")
            //->group("mes.sender_type")
            //->group("mes.id_sender")
            //->where("mes.id_receiver=u.id")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getModelInbox($id_model, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array(
                    //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                    "id as mes_id",
                    "subject",
                    "message",
                    "send_date",
                    "read",
                    "tip",
                    "user_type" => "sender_type",
                    "user_id" => "id_sender"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_receiver=?", $id_model)
            ->where("mes.receiver_type='model'")
            ->where("mes.type='inbox'")
            //->group("mes.id_receiver")
            //->group("mes.receiver_type")
            //->where("mes.id_sender=u.id")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getCountUnreadInbox($user_id = null, $user_type = null)
    {
        if ((!$user_id || !$user_type) && $user_id != 0) return false;

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array("count(id) as count"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_receiver=?", $user_id)
            ->where("mes.receiver_type=?", $user_type)
            ->where("mes.type='inbox'")
            ->where("mes.read='0'")
            //->group("mes.id_receiver")
            //->group("mes.receiver_type")
            //->where("mes.id_sender=u.id")
            ->order("mes.send_date desc")
//            ->limit($limit, $start);
;
        return $this->fetchRow($select);
    }

    public function getModelArchive($id_model, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array(
                    //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                    "id as mes_id",
                    "subject",
                    "message",
                    "send_date",
                    "read",
                    "tip",
                    "user_type" => "sender_type",
                    "user_id" => "id_sender"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_receiver=?", $id_model)
            ->where("mes.receiver_type='model'")
            ->where("mes.type='archive'")
            //->group("mes.id_receiver")
            //->group("mes.receiver_type")
            //->where("mes.id_sender=u.id")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getModeratorOutbox($id_mod, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array(
                    //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                    "id as mes_id",
                    "subject",
                    "message",
                    "send_date",
                    "read",
                    "tip",
                    "user_type" => "sender_type",
                    "user_id" => "id_sender"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_sender=?", $id_mod)
            ->where("mes.sender_type='moderator'")
            //->where("mes.id_sender=u.id")
            //->group("mes.id_receiver")
            //->group("mes.receiver_type")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getModeratorInbox($id_mod, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array(
                    //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                    "id as mes_id",
                    "subject",
                    "message",
                    "send_date",
                    "read",
                    "tip",
                    "user_type" => "receiver_type",
                    "user_id" => "id_receiver"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_receiver=?", $id_mod)
            ->where("mes.receiver_type='moderator'")
            ->where("mes.type='inbox'")
            //->where("mes.id_sender=u.id")
            //->group("mes.id_receiver")
            //->group("mes.receiver_type")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function getModeratorArchive($id_mod, $limit = 30, $start = 0)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("mes" => "messages"),
                array(
                    //new Zend_Db_Expr("if(mes.read = 1, count(id) , null) as count"),
                    "id as mes_id",
                    "subject",
                    "message",
                    "send_date",
                    "read",
                    "tip",
                    "user_type" => "receiver_type",
                    "user_id" => "id_receiver"))
            //->from(array("u" => "user"), "u.*")
            ->where("mes.id_receiver=?", $id_mod)
            ->where("mes.receiver_type='moderator'")
            ->where("mes.type='archive'")
            //->where("mes.id_sender=u.id")
            //->group("mes.id_receiver")
            //->group("mes.receiver_type")
            ->order("mes.send_date desc")
            ->limit($limit, $start);

        return $this->fetchAll($select);
    }

    public function deleteMessages($string = null)
    {

        if (!$string) return false;
        $string = trim($string, ',');
        //db()->delete("messages"," FIND_IN_SET(id, '".$string."')>0 ");
        db()->update("messages", array("type" => "delete"), " FIND_IN_SET(id, '" . $string . "')>0 ");
    }

    public function updateMessages($string = null, $read)
    {
        if (!$string) return false;
        $string = trim($string, ',');
        db()->update("messages", array("read" => $read), " FIND_IN_SET(id, '" . $string . "')>0 ");
    }

    public function archiveMessages($string = null)
    {
        if (!$string) return false;
        $string = trim($string, ',');
        db()->update("messages", array("type" => "archive"), " FIND_IN_SET(id, '" . $string . "')>0 ");
    }
}