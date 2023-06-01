<?

class Notifications extends App_Model{

    protected $_name="notifications";

    protected $_primary="id";

    /**
    * Get notifications for the options given
    *
    * @param mixed $from: date start
    * @param mixed $to: date end
    * @param mixed $filters: search options array(col == value)
    * @param mixed $name_from: user name from
    * @param mixed $name_to: user name to
    */
    public function getNotifications($from = null, $to = null, $filters = null, $name_from = null, $name_to = null){

        if(!is_null($name_from) && is_null($filters['id_from'])){
            $list = array();
            $list = $this->getUsersLike($name_from);

        }

        if(!is_null($name_to) && is_null($filters['id_to'])){
            $list1 = array();
            $list1 = $this->getUsersLike($name_to);
        }

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->order("date desc");

        if(!is_null($from)) $select->where("date>=?", $from);

        if(!is_null($to)) $select->where("date<=?", $to);

        if(!is_null($name_from) && is_null($filters['id_from'])) $select->where("locate(concat_ws('_', id_from, type_from), ?)!=0", $list);

        if(!is_null($name_to) && is_null($filters['id_to'])) $select->where("locate(concat_ws('_', id_to, type_to), ?)!=0", $list1);

        if(!is_null($filters)){
            foreach($filters as $key => $value){
                $select->where($key."=?", $value);
            }
        }

        return $this->fetchAll($select);
    }

    /**
    * get all users/models/moderators with name like $name
    *
    * @param mixed $name - user name to search fo
    * @return string - user list id_type,id1_type1 (ex 1_user,...)
    */
    public function getUsersLike($name){

        $list1 = array();
            $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("u" => "user"), array("concat_ws('_', id, 'user') as id"))
                       ->where("u.username like ?", "%".$name."%");
            $user_list = $this->fetchAll($select);
            if($user_list) $user_list = $user_list->toArray();
            else $user_list = null;
            foreach($user_list as $user){
                $list1[] = $user['id'];
            }
            unset($user_list);

            $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("u" => "user"), array("concat_ws('_', id, 'model') as id"))
                       ->where("concat_ws('_', u.first_name, u.name) like ?", "%".$name."%");
            $model_list = $this->fetchAll($select);
            if($model_list) $model_list = $model_list->toArray();
            else $model_list = null;
            foreach($model_list as $user){
                $list1[] = $user['id'];
            }
            unset($model_list);

            $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("u" => "moderator"), array("concat_ws('_', id, 'moderator') as id"))
                       ->where("u.username like ?", "%".$name."%");
            $moderator_list = $this->fetchAll($select);
            if($moderator_list) $moderator_list = $moderator_list->toArray();
            else $moderator_list = null;
            foreach($moderator_list as $user){
                $list1[] = $user['id'];
            }
            unset($moderator_list);

            return implode(",", $list1);

    }


    /**
    * Add notification
    *
    * @param mixed $id_from -> from user
    * @param mixed $from_type -> from user/model/moderator
    * @param mixed $id_to -> to user
    * @param mixed $to_type -> to user/model/moderator
    * @param mixed $type -> notification type
    * @param mixed $notification -> comments
    * @param mixed $read -> 1 - will be displayed in recent notification; 0 - read/system notification(login, etc)
    * @param mixed $ip -> received from ip
    */
    public function addNotification($id_from, $from_type, $id_to, $to_type, $type, $notification, $read = 1, $ip){
        $this->insert(array(
                      "id_from" => $id_from,
                      "type_from" => $from_type,
                      "id_to" => $id_to,
                      "type_to" => $to_type,
                      "type" => $type,
                      "notification" => $notification,
                      "read" => $read,
                      "ip" => $ip,
                      "date" => time()
                      ));
    }

}

