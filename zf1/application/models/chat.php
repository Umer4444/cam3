<?

/**
 * Class Chat
 */
class Chat
{
    /**
     * loads cchat files
     */
    public function __construct()
    {

        Zend_Loader::loadFile(APPLICATION_PATH . "/library/chat/CChat.php", null, true);
        Zend_Loader::loadFile(APPLICATION_PATH . "/library/chat/ChatBase.php", null, true);
        Zend_Loader::loadFile(APPLICATION_PATH . "/library/chat/ChatLine.php", null, true);
        Zend_Loader::loadFile(APPLICATION_PATH . "/library/chat/ChatUser.php", null, true);

    }

    /**
     * @param $name
     * @param $email
     * @param $model_id
     * @param $user_id
     * @param $chat_type
     * @param string $broadcast_mode
     * @param string $quality
     * @return array
     * @throws Exception
     */
    public function login($name, $email, $model_id, $user_id, $chat_type, $broadcast_mode = "", $quality = "")
    {
        return CChat::login($name, $email, $model_id, $user_id, $chat_type, $broadcast_mode, $quality);
    }

    /**
     * @param $model_id
     * @return array
     */
    public function logout($model_id)
    {
        return CChat::logout($model_id);
    }

    /**
     * @return bool
     */
    public function logoutAll()
    {
        return CChat::logoutAll();
    }

    /**
     * @param $model_id
     * @return array
     */
    public function checkLogged($model_id)
    {
        return CChat::checkLogged($model_id);
    }

    /**
     * @param $chatText
     * @param $model_id
     * @param $autoresponse
     * @param $type
     * @return array
     * @throws Exception
     */
    public function submitChat($chatText, $model_id, $autoresponse, $type)
    {
        return CChat::submitChat($chatText, $model_id, $autoresponse, $type);
    }

    /**
     * @param $model_id
     * @return array
     */
    public function getUsers($model_id, $service = null)
    {
        return CChat::getUsers($model_id, $service);
    }

    /**
     * @param $model_id
     * @return array
     */
    public function getChats($model_id)
    {
        return CChat::getChats($model_id);
    }

}    