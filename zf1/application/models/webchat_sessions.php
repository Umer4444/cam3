<?

class Webchat_sessions extends App_Model{

    protected $_name="webchat_sessions";

    protected $_primary="id";

    public function saveSession($idModel, $chatType, $idUser = null, $cameras = 1, $add_pending = null, $chat_ok = null){
        $session = $this->fetchRow($this->select()->where("id_model=?", $idModel));


        if($session){

            $data = array("chat_type" => $chatType);
            if($session->chat_type != $chatType)
                $data['timer'] = time();

            if(!is_null($idUser)) $data['id_user'] = str_replace('user_', '', $idUser);
            if ($add_pending) {
                $data['pending_users'] = (int)$session->pending_users + 1;
            }
            if ($chat_ok) {
                $data['users_count'] = (int)$session->pending_users + (int)$session->users_count;
                $data['pending_users'] = 0;
            }
            $this->update($data, $this->getAdapter()->quoteInto("id_model=?", $idModel));
            return true;

        }else{

            $data = array(
                "id_model" => $idModel,
                "chat_type" => $chatType,
                'timer' => time(),
                "cameras" => (int)$cameras,
                "session" => md5(microtime()),
            );
            $this->insert($data);

            return true;

        }

        return false;
    }

    public function deleteSession($idModel){

        return $this->delete($this->getAdapter()->quoteInto("id_model=?", $idModel));

    }

    public function getSession($idModel, $idUser = null){

        $select = $this->select()->where("id_model=?", $idModel);

        if(!is_null($idUser)) $select->where("id_user=?", $idUser);

        return $this->fetchRow($select);
    }
}