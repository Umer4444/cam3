<?php

class ProcessController extends App_Controller_Action
{

    protected $_ignoreLogin = array("viewitem", "photoalbum", "likes", 'imageverification', "getBlockRules", "modelSchedule", "request", "ajaxcall", "submitStatus", "getStatus", "submitNotes", "getNotes", 'addFavorite', 'removeFavorite', 'checkUserName', 'checkUserUniqueEmail', 'checkUniqueEmail', 'checkScreenName', 'suggestScreenName', 'imageverification2', 'checkCapchaContact', 'rating', 'submitAutoResponders', 'suggestAutoResponders'); // actions that don't need login
    protected $_ignorePost = array('imageverification2', "modelSchedule"); // actions allowed for GET requests

    var $_return = array();

    public function init()
    {
        parent::init();

        if (!in_array($this->_params['action'], $this->_ignoreLogin) && !in_array($_POST["action"], $this->_ignoreLogin) && Auth::isLogged()) {
            Auth::checkUser();
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!in_array($this->_params['action'], $this->_ignorePost) && !Zend_Registry::get("front")->getRequest()->isPost()) throw new \Exception('no action in post');

        $this->_helper->getHelper('layout')->disableLayout();
    }

    public function indexAction()
    {
        $action = $_POST["action"];
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();


        if (!method_exists($this, $action)) exit;
        else $this->$action();
    }

    //general request - for chat/ favorites/ notes/ user_requests/ etc
    private function request()
    {
        //unset($_SESSION['user_chat']['logout_time']);
        //unset($_SESSION['user_chat']['logout_message']);

        $this->_helper->layout->disableLayout();

        $this->load('chat');
        $this->load('model');
        $this->load('model_user_notes');
        $this->load("autoresponders_train");
        $this->load('model_requests');
        $this->load('webchat_users');
        $this->load("chips");
        $this->load('model_rates');

        //if chat not free
        if ($_SESSION['user_chat'][$this->_request->model_id]) $type = $_SESSION['user_chat'][$this->_request->model_id]['chat_type'];
        else $type = 'normal';


        list($usec, $sec) = explode(' ', microtime());

        if (Auth::isLogged() && $type != 'normal' && (((int)date('s', $sec) == 0 && (int)($usec * 1000000) > 500000) || ((int)date('s', $sec) == 30 && (int)($usec * 1000000) > 500000))) {

            $chat_types = array("private", "vip", "group", "3D");
            $chat_modes = array("spy", "show");


            if (in_array($type, $chat_types)) $type_rate = $type . "_chat";
            elseif (in_array($type, $chat_modes)) $type_rate = $type . "_mode";
            else $type_rate = $type;
            $amount = $this->model_rates->getModelRateByType($this->_request->model_id, $type_rate)->toArray();

            if ($amount[0]['value'] > 0) {
                $amount = $amount[0]['value'] / 2;
                //charge user

                if (Auth::isUser()) {
                    $this->chips->useChips($this->_request->model_id, $amount, user()->id, $type);
                }
            }


            if ($type == "private" && Auth::isModel())
                $this->_return["spy_users"] = $this->getSpyUsers($this->_request->model_id);

        }


        if (get_magic_quotes_gpc()) {

            // If magic quotes is enabled, strip the extra slashes
            array_walk_recursive($_GET, create_function('&$v,$k', '$v = stripslashes($v);'));
            array_walk_recursive($_POST, create_function('&$v,$k', '$v = stripslashes($v);'));
        }

        try {

            // Handling the supported actions:

            if ($this->_request->login == true) $this->chatLogin();
            if ($this->_request->logout == true) $this->chatLogout();
            if ($this->_request->getNotes == true) $this->getNotes();
            if ($this->_request->submitNotes == true) $this->submitNotes();
            if ($this->_request->submitChat == true) $this->chatSubmitChat();
            if ($this->_request->submitStatus == true) $this->submitStatus();
            if ($this->_request->submitStatusProfile == true) $this->submitStatus("profile");
            if ($this->_request->getStatus == true) $this->getStatus();
            if ($this->_request->addFavorite == true) $this->addFavorite();
            if ($this->_request->removeFavorite == true) $this->removeFavorite();

            if ($this->_request->returnRequest == true) $this->returnRequest();

            if ($this->_request->submitRequest == true) $this->submitRequest();
            if ($this->_request->acceptRequest == true) $this->acceptRequest();
            if ($this->_request->denyRequest == true) $this->denyRequest();
            if ($this->_request->removeRequest == true) $this->removeRequest();
            if ($this->_request->checkLogged == true) $this->chatCheckLogged();

            if ($this->_request->submitrequest_request != 'spy' && $_SESSION['user_chat'][$this->_request->model_id]['chat_type'] != 'spy') {
                if ((int)date('s', $sec) % 5 == 0 && (int)($usec * 1000000) > 500000) $this->chatGetUsers();
                $this->chatGetChats();
            }

            $this->checkUserAccess();

            $this->checkGroupPending();

            if (Auth::isUser() || Auth::isModel()) $this->getChips();
            echo json_encode($this->_return);

        } catch (Exception $e) {
            print_r($e);
            die(json_encode(array('error' => $e->getMessage())));
        }
        return;
    }

    private function chatLogin()
    {

        $this->load("chat");

        $this->_return['login'] = $this->chat->login($this->_request->login_name, $this->_request->login_email, $this->_request->model_id, $this->_request->login_user_id, $this->_request->login_chat_type);
        $this->load("notifications");
        if (Auth::isModel()) {
            $this->notifications->addNotification(user()->id, "model", user()->id, "model", "chat-login", "login", 1, getUserIp());

            $chat = $this->webchat_sessions->getSession(user()->id);

            //init chat session if not started(model refresh)
            $this->load("webchat_sessions");
            $this->webchat_sessions->saveSession(user()->id, user()->chat_type);



        }
        if (Auth::isUser()) {
            $this->notifications->addNotification(user()->id, "user", user()->id, "user", "chat-login", "login", 1, getUserIp());
        }

    }

    private function chatLogout()
    {

        $this->load("chat");
        $this->load("model");
        $this->load("notifications");
        $this->_return['logout'] = $this->chat->logout($this->_request->model_id);
        if (Auth::isModel()) {
            $this->notifications->addNotification(user()->id, "model", user()->id, "model", "chat-logout", "logout", 1, getUserIp());
            $_SESSION['user']['chat_type'] = 'normal';
            $this->model->setChatType(user()->id, 'normal');

            //delete chat session
            $this->load("webchat_sessions");
            $this->webchat_sessions->deleteSession(user()->id);

        }
        if (Auth::isUser()) {
            $this->notifications->addNotification(user()->id, "user", user()->id, "user", "chat-logout", "logout", 1, getUserIp());
        }
    }

    private function getNotes()
    {
        $this->load("model_user_notes");

        $notes = $this->model_user_notes->getUserNotesById(user()->id, $this->_request->getnotes_user_id);

        if ($notes) $notes = $notes->toArray();
        else {
            $notes = array();
            $notes['id_user'] = $this->_request->getnotes_user_id;
        }
        $this->_return['getNotes'] = $notes;
    }

    private function submitNotes()
    {
        $this->load("model_user_notes");

        $this->_return['submitNotes'] = $this->model_user_notes->setNotes(user()->id, $this->_request->submitnotes_user_id, $this->_request->submitnotes_notes);
        $this->load("notifications");
        $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-notes", "user " . $this->_request->submitnotes_user_id . " notes changed", 1, getUserIp());
    }

    private function chatGetUsers()
    {
        $this->load("chat");

        if (is_null($this->_request->login) && is_null($this->_request->checkLogged) && is_null($this->_request->logout)) {
            //@todo move routes to zf2 when features moved
            $users = $this->chat->getUsers($this->_request->model_id, $this->view);
            $users_list = $users['users'];
            //sort users

            usort($users_list, "cmp_webchat_users");

            $users['users'] = $users_list;

            $this->_return['getUsers'] = $users;

            if (Auth::isModel()) {
                //$this->_return["chat_no_users"] = 1;
                if (count($users['users']) > 1) {
                    unset($_SESSION["chat_no_users"]);
                } elseif (count($users['users']) <= 1 && !isset($_SESSION["chat_no_users"])) {
                    $_SESSION["chat_no_users"] = 1;
                    $this->_return["chat_no_users"] = 1;
                }
            }
        }
    }

    private function chatGetChats()
    {
        $this->load("chat");
        $this->load("autoresponders_train");
        //check if logged
        if (is_null($this->_request->login) && is_null($this->_request->checkLogged) && is_null($this->_request->logout)) {
            $chats = $this->chat->getChats($this->_request->model_id);

            if (Auth::isModel()) {

                $autoresponders = $this->autoresponders_train->getAutorespondersByModel($this->_request->model_id);

                foreach ($chats['chats'] as $key_m => $chat_line) {

                    if ($chat_line['author'] != user()->screen_name) {//check questions
                        //initialize auto responders

                        foreach ($autoresponders as $key => $value) {
                            if ($value['question'] == $chat_line['text'] && $chat_line['responded'] == 0) {
                                $chat_line['responded'] == 1;
                                $chats['chats'][$key_m]['responded'] == 1;

                                db()->query("UPDATE webchat_lines SET
                                    " . db()->quoteInto("responded=?", 1) . " WHERE
                                    " . db()->quoteInto("id=?", $chat_line['id'])
                                );

                                //get autoresponse - only if not previously answered.
                                //this does not work properly!
                                $answer_id = rand(0, count($value['answers']) - 1);
                                //update chat line set responded=1 ! -- TO DO !


                                $this->chat->submitChat($value['answers'][$answer_id]['message'], $this->_request->model_id, '1', 'normal');
                            }

                        }

                    }

                }

            }

            $this->_return['getChats'] = $chats;
        }
    }

    private function chatSubmitChat()
    {
        $this->load("chat");
        $this->load("chips");

        switch ($this->_request->submitchat_line_type) {

            case 'tip':
                if ((int)$this->_request->submitchat_text > 0) {
                    $this->chips->useChips($this->_request->model_id, $this->_request->submitchat_text, user()->id, 'tip');
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "user", $this->_request->model_id, "model", "tip", "user tipped " . $this->_request->submitchat_text . " chips!", 1, getUserIp());
                }
                break;

        }

        /* bad words filters */
        $this->load("bad_words");
        $badWords = $this->bad_words->getAllArray();

        $text = preg_replace($badWords["words"], $badWords["replacements"], $this->_request->submitchat_text);

        $this->_return['submitChat'] = $this->chat->submitChat($text, $this->_request->model_id, $this->_request->submitchat_autoresponse, $this->_request->submitchat_line_type);
    }

    private function chatCheckLogged()
    {
        $this->load("chat");

        $this->_return['checkLogged'] = $this->chat->checkLogged($this->_request->model_id);

        if (Auth::isModel()) {
            if ($this->_return['checkLogged']['logged']) {
                //init chat session if not started(model refresh)
                $this->load("webchat_sessions");
                $this->webchat_sessions->saveSession(user()->id, $this->_return['checkLogged']['loggedAs']['chat_type']);
            }
        }

    }

    private function getStatus()
    {
        $this->load("model");

        $this->_return['getStatus'] = $this->model->getStatus($this->_request->model_id);
        return;
    }

    /**
     * @param string $type
     * @throws Zend_Exception
     */

    private function submitStatus($type = "room")
    {
        $this->load("model");

        if ($type == 'room') {
            $_SESSION['user']['status'] = $this->_request->submitstatus_status;
            $this->_return['submitStatus'] = $this->model->setStatus($this->_request->model_id, $this->_request->submitstatus_status);
        } else if ($type == 'profile') {
            $_SESSION['user']['status_profile'] = $this->_request->submitstatus_status_profile;
            $this->_return['submitStatusProfile'] = $this->model->setStatus($this->_request->model_id, $this->_request->submitstatus_status_profile, 'profile');
        }

        $this->load("notifications");
        $this->notifications->addNotification($_SESSION['user']['id'], "model", $_SESSION['user']['id'], "model", "model-status", "change", 1, getUserIp());
    }

    /**
     * @param null $id
     * @return bool
     * @throws Zend_Exception
     */
    private function getSpyUsers($id = null)
    {
        if (!$id) return false;
        $this->load("webchat_users");

        return $this->webchat_users->countUsers($id, "spy");
    }

    private function getChips()
    {

        $this->load('chips');
        if (is_null($this->_request->login) && is_null($this->_request->checkLogged) && is_null($this->_request->logout)) {
            if ($_SESSION['group'] == 'model') {

                $nr = intval($this->chips->getChips(null, $_SESSION['user']['id']));
                if ($_SESSION['user']['chips'] != $nr) {
                    $this->_return['getChips'] = $nr;
                    $_SESSION['user']['chips'] = $this->_return['getChips'];
                }

            } elseif ($_SESSION['group'] == 'user') {

                $nr = intval($this->chips->getChips($_SESSION['user']['id']));
                if ($_SESSION['user']['chips'] != $nr) {
                    $this->_return['getChips'] = $nr;
                    $_SESSION['user']['chips'] = $this->_return['getChips'];
                    if ($nr < 100) $this->_return['openLink'] = "/purchase-chips/";
                }
            } else {
                //$this->_return['getChips'] = 0;
            }
        }

    }

    private function returnRequest()
    {
        $this->load("model_requests");
        $this->load("chat");

        if (Auth::isLogged()) {
            if (!$this->request->submitRequest) {

                $this->_request->returnrequest_user_id ? $user_id = $this->_request->returnrequest_user_id : $user_id = null;
                $this->_request->returnrequest_request ? $type = $this->_request->returnrequest_request : $type = null;

                $this->_return['returnRequest'] = $this->model_requests->getRequests($this->_request->model_id, $user_id, $type)->toArray();
                if ($this->_return['returnRequest'][0]['type'] == "group") {

                    //if accepted
                    if ($this->_return['returnRequest'][0]['status'] == 'accepted') {
                        $_SESSION['user_chat'][$this->_request->model_id]['chat_type'] = $this->_return['returnRequest'][0]['type'];
                        if ($this->_return['returnRequest'][0]['type'] != 'spy') {
                            $this->model_requests->deleteRequest($this->_request->model_id, user()->id, $this->_return['returnRequest'][0]['type']);
                        }
                        $this->chat->getUsers(user()->id, $this->view);

                    }

                } else {
                    //p($user_id);
                    //if type = spy auto - accept
                    if ($this->_return['returnRequest'][0]['type'] == 'spy') {
                        $this->_return['returnRequest'][0]['status'] = 'accepted';
                    }

                    //if accepted
                    if ($this->_return['returnRequest'][0]['status'] == 'accepted') {
                        $_SESSION['user_chat'][$this->_request->model_id]['chat_type'] = $this->_return['returnRequest'][0]['type'];
                        if ($this->_return['returnRequest'][0]['type'] != 'spy') {
                            $this->model_requests->deleteRequest($this->_request->model_id, user()->id, $this->_return['returnRequest'][0]['type']);
                        }


                        $this->chat->getUsers(user()->id, $this->view);

                    }
                }
            }
        }
    }

    private function submitRequest()
    {
        $this->load("model_requests");
        $this->load("chat");
        $this->load("model");
        $this->load("model_rates");

        $model_id = $this->_request->model_id;
        $user_id = $_SESSION['user_chat'][$model_id]['id'];

        $model_db = $this->model->fetchRow($this->model->select()->from("model")->where("id=?", $model_id));

        if (!Auth::isModel()) {
            //if user has less chips than minimum amount - do not submit request

            $type = $this->_request->submitrequest_request;

            if ($type == 'private' || $type == "vip") {
                $member_cam = $this->_request->submitrequest_memberCamera;
            } else
                $member_cam = 0;

            $chat_types = array("private", "vip", "group", "3D");
            $chat_modes = array("spy", "show");

            if (in_array($type, $chat_types)) $type_rate = $type . "_chat";
            elseif (in_array($type, $chat_modes)) $type_rate = $type . "_mode";
            else $type_rate = $type;

            $amount = $this->model_rates->getModelRateByType($this->_request->model_id, $type_rate)->toArray();

            //check user chips - if enough - send request
            if (Auth::isUser() && $type != 'normal') {
                if (user()->chips >= $amount[0]['value']) {

                    $this->_return['submitRequest'] = $this->model_requests->addRequest($this->_request->model_id, $this->_request->submitrequest_user_id, $this->_request->submitrequest_request, 'pending', $member_cam);
                    $_SESSION["member_camera"] = $member_cam;

                    if ($this->_request->submitrequest_request == 'spy') {
                        $chatName = (

                        user()->screen_name ?
                            user()->screen_name : (
                        user()->display_name ? user()->display_name :
                            (user()->username ? user()->username : 'performer' . user()->id)
                        )
                        );

                        $this->chat->login($chatName, user()->email, $this->_request->model_id, "user_" . user()->id, $this->_request->submitrequest_request);

                    }

                } else {
                    $this->_return['submitRequest'] = array(array(
                        'count' => 0,
                        'id_model' => $this->_request->model_id,
                        'id_user' => user()->id,
                        'type' => $this->_request->submitrequest_request,
                        'status' => 'not enough chips'
                    ));
                }
            }
        }

    }

    private function acceptRequest()
    {
        $this->load("chat");
        $this->load("model");
        $this->load("model_requests");
        $this->load("webchat_sessions");

        $accepted_req = false;
        $accepted_group = false;

        if ($this->_request->acceptrequest_request) {
            if ($this->_request->acceptrequest_request == "group") {
                $this->webchat_sessions->saveSession(user()->id, 'normal', null, null, true);
                $webchat = $this->webchat_sessions->getSession(user()->id);
                if ((int)$webchat->pending_users + (int)$webchat->users_count >= (int)config()->min_group_users) {

                    $this->webchat_sessions->saveSession(user()->id, 'normal', null, null, null, true);

                    $accepted_req = false;
                    $accepted_group = true;
                }
                $this->model_requests->updateGroupRequest($this->_request->model_id, $this->_request->acceptrequest_request, 'pending', $this->_request->acceptrequest_user_id);

            } else {
                $accepted_req = true;
            }

            if ($accepted_group) {
                $_SESSION['user_chat'][user()->id]['chat_type'] = $this->_request->acceptrequest_request;
                $_SESSION['user']['chat_type'] = $this->_request->acceptrequest_request;

                $this->model_requests->updateGroupRequest($this->_request->model_id, $this->_request->acceptrequest_request, 'accepted');

                $this->model->setChatType(user()->id, $this->_request->acceptrequest_request);

                //update chat session
                $this->load("webchat_sessions");
                $this->webchat_sessions->saveSession(user()->id, $this->_request->acceptrequest_request);

                if (Auth::isModel()) {
                    $this->load("webchat_users");
                    $this->webchat_users->updateUserChat(user()->id, "model", $this->_request->acceptrequest_request);

                }
                $this->_return['acceptRequest'] = $this->model_requests->getGroupRequest($this->_request->model_id,
                    $this->_request->acceptrequest_user_id,
                    $this->_request->acceptrequest_request,
                    'accepted');


            }

            if ($accepted_req) {
                $_SESSION['user_chat'][user()->id]['chat_type'] = $this->_request->acceptrequest_request;
                $_SESSION['user']['chat_type'] = $this->_request->acceptrequest_request;
                $this->chat->getUsers(user()->id, $this->view);
                $this->model->setChatType(user()->id, $this->_request->acceptrequest_request);

                //update chat session
                $this->webchat_sessions->saveSession(user()->id, $this->_request->acceptrequest_request, $this->_request->acceptrequest_user_id);

                if (Auth::isModel()) {
                    $this->load("webchat_users");
                    $this->webchat_users->updateUserChat(user()->id, "model", $this->_request->acceptrequest_request);
                }
                $this->_return['acceptRequest'] = $this->model_requests->updateRequest($this->_request->model_id, $this->_request->acceptrequest_user_id, $this->_request->acceptrequest_request, 'accepted');


            }


        }


        if ($this->_return['acceptRequest'][0]["member_camera"]) $_SESSION["member_camera"] = true;
        else $_SESSION["member_camera"] = false;

    }

    private function denyRequest()
    {
        $this->load("model_requests");

        $this->_return['denyRequest'] = $this->model_requests->denyRequest($this->_request->model_id, $this->_request->denyrequest_user_id, $this->_request->denyrequest_request);
    }

    private function removeRequest()
    {
        $this->load("model_requests");

        $this->_request->removerequest_user_id ? $user_id = $this->_request->removerequest_user_id : $user_id = null;
        $this->_request->removerequest_request ? $type = $this->_request->removerequest_request : $type = null;

        $this->_return['removeRequest'] = $this->model_requests->deleteRequest($this->_request->model_id, $user_id, $type);

    }

    private function checkUserAccess()
    {

        $this->load("webchat_users");
        $this->load("model_rates");
        $this->load("model");
        $this->load("model_user_access");


        $model_id = $this->_request->model_id;
        $user_id = $_SESSION['user_chat'][$model_id]['id'];

        // check if user restricted is in model_user_access
        $restiction = $this->model_user_access->getRestrictedWeb($model_id, $user_id);

        $authorized = true;
        $return = array("status" => "authorized");

        if (Auth::isUser() && in_array($user_id, $restiction['user'])) {
            $authorized = false;
            $this->_return['user_ban'] = "Free time expired. Take action. ";
        } elseif (!Auth::isLogged() && in_array($_SERVER["REMOTE_ADDR"], $restiction['ip'])) {
            $authorized = false;
            $this->_return['user_ban'] = "Free time expired. Login";
        }


        $model_db = $this->model->fetchRow($this->model->select()->from("model")->where("id=?", $model_id));

        if (is_null($this->_request->login) && is_null($this->_request->checkLogged) && is_null($this->_request->logout) && is_null($this->_request->returnRequest) && is_null($this->_request->removeRequest) && is_null($this->_request->submitRequest)) {
            if ($user_id) {
                if (!Auth::isModel()) {   //if user has less chips than minimum amount - logout


                    if ($_SESSION['user_chat'][$this->_request->model_id]) $type = $_SESSION['user_chat'][$this->_request->model_id]['chat_type'];
                    else $type = 'normal';

                    $chat_types = array("private", "vip", "group", "3D");
                    $chat_modes = array("spy", "show");

                    if (in_array($type, $chat_types)) $type_rate = $type . "_chat";
                    elseif (in_array($type, $chat_modes)) $type_rate = $type . "_mode";
                    else $type_rate = $type;

                    $amount = $this->model_rates->getModelRateByType($this->_request->model_id, $type_rate)->toArray();


                    //check user chips

                    if (Auth::isUser() && $type != 'normal') {

                        // if(user()->chips < $amount[0]['value']) {
                        if (user()->chips < $amount[0]['value']) {
                            $authorized = false;
                            if (!isset($_SESSION['user_chat']['logout_time']['time'])) {
                                $_SESSION['user_chat']['logout_time']['time'] = time() + (config()->max_allowed_time_without_chips * 60);

                                $this->_return['chips_message'] = "Not enough chips. You will be disconected after " . config()->max_allowed_time_without_chips . " minutes. Purchase more chips!";
                            }
                        }
                    }


                    $model = $this->webchat_users->getUser($model_id, 'model_' . $model_id);
                    if ($model) $model = $model->toArray();
                    if ($model) {
                        $user = $this->webchat_users->getUser($model_id, $user_id);

                        //get model chat session - to check if user is registered(-for a private chat)
                        $this->load("webchat_sessions");
                        $webchatSession = $this->webchat_sessions->getSession($model_id, $user_id);
                        if ($user) $user = $user->toArray();

                        if ($model['chat_type'] != 'normal') {

                            if (($user['chat_type'] != $model['chat_type'] && !($webchatSession)) && $user['chat_type'] != 'spy') {
                                $authorized = false;
                            }

                        } else {
                            if ($user['chat_type'] != $model['chat_type']) {
                                $authorized = false;

                            }
                        }
                    } else {
                        $authorized = false;
                    }

                }
            } else {
                $model = $this->webchat_users->getUser($model_id, 'model_' . $model_id);
                if ($model) $model = $model->toArray();
                if (!$model) {
                    $authorized = false;
                }

            }

        }


        if (!$user_id && is_null($this->_request->submitRequest)) {

            $type = $model_db->chat_type;
            if ($type != 'normal') $authorized = false;

        }

        if (Auth::isUser() || !Auth::isLogged()) {
            if (!is_null($this->_request->returnRequest)) {
                $_SESSION['stream'] = $model_db->getStream();


                //model accepted request - send stream to user
                if ($this->_return['returnRequest'][0]['status'] == 'accepted' && $this->_return['returnRequest'][0]['type'] != 'spy') {
                    $return['stream'] = $model_db->getStream($this->_return['returnRequest'][0]['id_user']);
                    $_SESSION['stream'] = $model_db->getStream($this->_return['acceptRequest'][0]['id_user']);

                }

                if ($this->_return['returnRequest'][0]['status'] == 'accepted' && $this->_return['returnRequest'][0]['type'] == 'spy') {
                    $id_user = $this->webchat_users->getPrivateUserId($model_id);
                    $return['stream'] = $model_db->getStream($id_user);
                    $_SESSION['stream'] = $model_db->getStream($id_user);

                }

                $channelName = "model_" . $model_id;
                $_SESSION['channel'] = $channelName;

                $rtmp = "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/" . $channelName;
                //$_SESSION['rtmp'] = $rtmp;

                $channel = array(
                    "channel" => $channelName,
                    "password" => "PASSWORD",
                    "stream" => $_SESSION['stream'],
                    "rtmfp" => "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/"
                );

                $_SESSION['new_channel'] = $channel;

                $return['streamer'] = config()->rtmp;

                $return['type'] = 'user';
            }

            if ($type == 'normal') {
                if (!isset($_SESSION['user_chat']['logout_time']['time'])) {
                    $_SESSION['user_chat']['logout_time']['time'] = time() + (config()->max_allowed_time * 60);
                    if (!isset($_SESSION['user_chat']['logout_message'])) {
                        $_SESSION['user_chat']['logout_message'] = "Free preview for " . config()->max_allowed_time . " minutes.";
                    }

                    $this->_return['chips_message'] = $_SESSION['user_chat']['logout_message'];
                }
                if (time() > $_SESSION['user_chat']['logout_time']['time']) {
                    $authorized = false;
                }
            }
        }

        if (Auth::isModel()) {
            if (!is_null($this->_request->acceptRequest)) {

                //model accepted request
                $return['stream'] = $model_db->getStream($this->_return['acceptRequest'][0]['id_user']);
                $_SESSION['stream'] = $model_db->getStream($this->_return['acceptRequest'][0]['id_user']);

                $channelName = "model_" . $model_id;
                $_SESSION['channel'] = $channelName;

                $rtmp = "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/" . $channelName;
                //$_SESSION['rtmp'] = $rtmp;

                $channel = array(
                    "channel" => $channelName,
                    "password" => "PASSWORD",
                    "stream" => $_SESSION['stream'],
                    "rtmfp" => "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/"
                );

                $_SESSION['new_channel'] = $channel;

                $return['streamer'] = config()->rtmp;
                $return['type'] = 'model';

            } else {
                $this->load("webchat_sessions");
                $webchatSession = $this->webchat_sessions->getSession(user()->id);
                if (!$webchatSession && user()->chat_type != "normal" && !$this->_request->logout && !$this->_request->login && !$this->_request->checkLogged && !$this->_request->returnRequest) {
                    $authorized = false;
                    $this->model->setChatType(user()->id, "normal");
                }
            }
        }


        $userLogged = $this->chat->checkLogged($model_id);

        if (!Auth::isLogged() && $model_db->chat_type != "normal") { //delete user

            if ($userLogged["logged"]) $this->chat->logout($model_id);

            //$x = db()->query("select * from webchat_users")->execute()->fetchAll();
            //mail("razvan.moldovan@perfectweb.ro","q", print_r($_SESSION["user_chat"],1).print_r($_SESSION["user"],1).print_r($x,1)) ;
            $return['status'] = "unauthorized";
            $this->_return['checkUserAccess'] = $return;
        }
        //verify authorization
        if ($authorized) {
            if ($return['stream']) $this->_return['checkUserAccess'] = $return;

        } else {

            //logout of chat after some time
            if (time() >= $_SESSION['user_chat']['logout_time']['time']) {

                //logout user from chat
                unset($_SESSION['user_chat']['logout_time']);

                $_SESSION['user_chat']['logout_message'] = "Time's up!";

                if (Auth::isUser() && $type != 'normal') {

                    if ($_SESSION['user_chat'][$model_id]['chat_type'] != "spy") {


                        //chat - logout
                        if ($user_id) {

                            db()->query("DELETE FROM webchat_users WHERE "
                                //.db()->quoteInto("id_user=?",  "model_".$model_id)." OR "
                                . db()->quoteInto("id_model=?", $model_id)
                            )->execute();

                            db()->query("DELETE FROM webchat_sessions WHERE "
                                //".db()->quoteInto("id_user=?", $user_id)." AND
                                . db()->quoteInto("id_model=?", $model_id)
                            )->execute();


                            if ($_SESSION['group'] == 'user') { //delete pending requests

                                db()->query("DELETE FROM model_requests WHERE
                                            " . db()->quoteInto("id_user=?", user()->id) . " AND
                                            " . db()->quoteInto("id_model=?", "model_" . $model_id)
                                )->execute();

                            }
                        }

                    } elseif ($_SESSION['user_chat'][$model_id]['chat_type'] == "spy") { //if user is spy remove only the user
                        db()->query("DELETE FROM webchat_users WHERE "
                            . db()->quoteInto("chat_type=?", "spy") . " AND " . db()->quoteInto("id_model=?", $model_id)
                        )->execute();
                    }


                    //unset chat session
                    unset($_SESSION['user_chat'][$model_id]);


                    //switch back to normal stream
                    $return['stream'] = $model_db->getStream();
                    $return['streamer'] = config()->rtmp;
                    $_SESSION['stream'] = $model_db->getStream();

                    $channelName = "model_" . $model_id;
                    $_SESSION['channel'] = $channelName;

                    $rtmp = "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/" . $channelName;
                    //$_SESSION['rtmp'] = $rtmp;

                    $channel = array(
                        "channel" => $channelName,
                        "password" => "PASSWORD",
                        "stream" => $_SESSION['stream'],
                        "rtmfp" => "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/"
                    );

                    $_SESSION['new_channel'] = $channel;

                    if (Auth::isModel()) $return['type'] = 'model';
                    if (Auth::isUser()) $return['type'] = 'user';

                    $return['status'] = "unauthorized";
                    $this->_return['checkUserAccess'] = $return;
                }

                if (!Auth::isModel() && $type == 'normal') {
                    //adaugare  in model_user_access
                    //daca e user logat avem id, daca nu aveam ip
                    //reason: time-up

                    $this->load("model_user_access");
                    $query = db()->quoteInto("(id_user =?", user()->id);
                    $query .= db()->quoteInto(" OR ip =?)", $_SERVER["REMOTE_ADDR"]);
                    $query .= db()->quoteInto(" AND id_model =?", $model_id);
                    $query .= db()->quoteInto(" AND action =?", 4);
                    $query .= db()->quoteInto(" AND reason =?", "time_up");
                    $this->model_user_access->delete($query);
                    //$this->model_user_access->delete(array("id_user =?" => user()->id, "id_model =?" => $model_id, "action =?" => 4, "reason =?" => "time_up" ));
                    $this->model_user_access->insert(array("id_model" => $model_id, "id_user" => user()->id, "setting" => 1, "action" => 4, "from" => time(), "to" => time() + (24 * 3600), "reason" => "time_up", "ip" => $_SERVER["REMOTE_ADDR"]));

                }

            } else {
                if ($_SESSION['group'] == 'user') { //delete user

                    db()->query("DELETE FROM model_requests WHERE
                            " . db()->quoteInto("id_user=?", user()->id) . " AND
                            " . db()->quoteInto("id_model=?", "model_" . $model_id)
                    )->execute();

                    db()->query("DELETE FROM webchat_users WHERE "
                        . db()->quoteInto("id_user=?", "user_" . user()->id) . " AND "
                        . db()->quoteInto("id_model=?", $model_id)
                    )->execute();
                    //$x = db()->query("select * from webchat_users")->execute()->fetchAll();
                    //mail("razvan.moldovan@perfectweb.ro","q", print_r($_SESSION["user_chat"],1).print_r($_SESSION["user"],1).print_r($x,1)) ;
                    $return['status'] = "unauthorized";
                    $this->_return['checkUserAccess'] = $return;
                }


            }
        }
    }

    private function checkGroupPending()
    {
        $this->load("webchat_sessions");
        $webchat = $this->webchat_sessions->getSession((int)$this->_request->model_id);
        if ($webchat->users_count == 0 && $webchat->pending_users > 0)
            $this->_return["pending_requests"] = $webchat->pending_users;
        else
            $this->_return["pending_requests"] = 0;

    }

    private function addFavorite()
    {

        if (get_magic_quotes_gpc()) {

            // If magic quotes is enabled, strip the extra slashes
            array_walk_recursive($_GET, create_function('&$v,$k', '$v = stripslashes($v);'));
            array_walk_recursive($_POST, create_function('&$v,$k', '$v = stripslashes($v);'));
        }

        try {

            $response = array();

            $this->load('user_favorites');
            $response = $this->user_favorites->addFavorite($this->_request->model_id, $_SESSION['user']['id']);
            $this->load("notifications");
            $this->notifications->addNotification($_SESSION['user']['id'], "user", $this->_request->model_id, "model", "user-favorites", "add model", 1, getUserIp());
            echo json_encode($response);
        } catch (Exception $e) {
            die(json_encode(array('error' => $e->getMessage())));
        }
    }

    private function removeFavorite()
    {

        if (get_magic_quotes_gpc()) {

            // If magic quotes is enabled, strip the extra slashes
            array_walk_recursive($_GET, create_function('&$v,$k', '$v = stripslashes($v);'));
            array_walk_recursive($_POST, create_function('&$v,$k', '$v = stripslashes($v);'));
        }

        try {

            $response = array();

            $this->load('user_favorites');
            $response = $this->user_favorites->removeFavorite($this->_request->model_id, $_SESSION['user']['id']);
            $this->load("notifications");
            $this->notifications->addNotification($_SESSION['user']['id'], "user", $this->_request->model_id, "model", "user-favorites", "remove model", 1, getUserIp());
            echo json_encode($response);
        } catch (Exception $e) {
            die(json_encode(array('error' => $e->getMessage())));
        }
    }

    private function saveNotes()
    {

        $this->load('model_user_notes');

        $id_user = $this->request->id_user;
        $type = $this->request->type;

        if ($id_user > 0) {
            if (trim($this->request->message) != '') {
                $this->model_user_notes->update(array("notes" => $this->request->message, "added" => time()), $this->db->quoteInto("id_user=?", $id_user) . " and " . $this->db->quoteInto("id_model=?", $_SESSION['user']['id']));
                echo json_encode(array('response' => 'ok'));
            } else {
                $this->model_user_notes->delete($this->db->quoteInto("id_user=?", $id_user) . " and " . $this->db->quoteInto("id_model=?", $_SESSION['user']['id']));
                echo json_encode(array('response' => 'deleted'));
            }

        } else {
            //INSERT

            if (trim($this->request->message) == '') exit;
            $data = array("id_model" => $_SESSION['user']['id'], "id_user" => $this->request->id_user, "notes" => $this->request->message, "added" => time());
            $this->model_user_notes->insert($data);

            echo json_encode(array('response' => 'ok'));

        }

        $this->load("notifications");
        $this->notifications->addNotification($_SESSION['user']['id'], "model", $this->request->id_user, "user", "model-notes", "save notes for user " . $this->request->id_user, 1, getUserIp());
    }

    private function submitAutoResponders()
    {

        $this->load('autoresponders');
        $this->load('autoresponders_train');

        /* bad words filters */

        $this->load("bad_words");
        $badWords = $this->bad_words->getAllArray();


        /* end bad words filter */

        list($field_type, $field_id) = explode('-', $this->request->field_id);

        if ($field_id > 0) {
            if (trim($this->request->message) != '') {
                $data = array("message" => $this->request->message);
                array_walk($data, 'badWords', $badWords);
                $this->autoresponders->update($data, $this->db->quoteInto("id=?", $field_id) . " and " . $this->db->quoteInto("type=?", $field_type));
                echo json_encode(array('response' => 'ok'));
            } else {
                // delete only from link table ,leave question/answers for autosuggest
                $this->autoresponders_train->delete($this->db->quoteInto("id_" . $field_type . "=?", $field_id) . " and " . $this->db->quoteInto("id_model=?", $_SESSION['user']['id']));
                echo json_encode(array('response' => 'deleted'));
            }

        } else {
            //INSERT
            if ($field_type == 'question') {

                $exists = $this->autoresponders->fetchRow($this->db->quoteInto("message=?", $this->request->message) . " and " . $this->db->quoteInto("type=?", $field_type));
                if ($exists->id) {
                    $last_question_id = $exists->id;
                } else {
                    $data = array("message" => $this->request->message);
                    array_walk($data, 'badWords', $badWords);
                    $data["type"] = $field_type;
                    $this->autoresponders->insert($data);
                    $last_question_id = $this->db->lastInsertId();
                }

                echo json_encode(array('response' => 'question_adeed', 'id_question' => $last_question_id, 'message' => $this->request->message));
            } else {
                if (!$this->request->id_question || trim($this->request->message) == '') exit;

                $exists = $this->autoresponders->fetchRow($this->db->quoteInto("message=?", $this->request->message) . " and " . $this->db->quoteInto("type=?", $field_type));
                if ($exists->id) {
                    $last_answer_id = $exists->id;
                } else {
                    $data = array("message" => $this->request->message);
                    array_walk($data, 'badWords', $badWords);
                    $data["type"] = $field_type;

                    $this->autoresponders->insert($data);
                    $last_answer_id = $this->db->lastInsertId();
                }

                $this->autoresponders_train->insert(array("id_model" => $_SESSION['user']['id'], "id_question" => $this->request->id_question, "id_answer" => $last_answer_id));

                echo json_encode(array('response' => 'ok', "answer_id" => $last_answer_id));
            }
        }

        $this->load("notifications");
        $this->notifications->addNotification($_SESSION['user']['id'], "model", $_SESSION['user']['id'], "model", "model-autoresponders", "edit autoresponders", 1, getUserIp());
    }

    private function suggestAutoResponders()
    {

        $this->load('autoresponders');

        $ttype = $this->_request->ttype == 'a' ? 'answer' : 'question';
        $searches = $this->autoresponders->searchAutoResponders($this->request->query, $ttype);

        $results = array();
        foreach ($searches as $search) {
            $results[] = $search['message'];
        }
        echo json_encode(array('query' => $this->request->query, 'suggestions' => $results));
    }

    private function suggestSendToModel()
    {
        $this->load('model');

        $searches = $this->model->searchModelsByName($this->request->query);

        $results = array();
        foreach ($searches as $search) {
            $results[] = $search['screen_name'];
        }
        echo json_encode(array('query' => $this->request->query, 'suggestions' => $results));
    }

    private function suggestSendToUser()
    {
        $this->load('user');

        $searches = $this->user->searchUserByName($this->request->query);

        $results = array();
        foreach ($searches as $search) {
            $results[] = $search['username'];
        }
        echo json_encode(array('query' => $this->request->query, 'suggestions' => $results));
    }

    private function suggestScreenName()
    {

        $list = array("white", "black", "red", "the_one", "2000", "2k", "01", "02", "03", "04", "05", "06", "07", "08", "09");
        $separator = array("", "-", "_");

        for ($k = 10; $k <= 99; $k++) {
            $list[] = $k;
        }

        shuffle($list);

        $i = 0;
        $results = '<div id="theresults">';
        foreach ($list as $item) {
            shuffle($separator);
            if ($i >= 6) break;
            $i++;
            $results .= '<a onclick="addScreenname(\'' . $this->request->query . $separator[0] . $item . '\');" class="box_screenname" href="javascript:;">' . $this->request->query . $separator[0] . $item . '</a>';
        }
        $results .= "<br><br></div>";
        echo json_encode(array('query' => $this->request->query, 'suggestions' => $results));
    }

    private function suggestNickName()
    {

        $list = array("white", "black", "red", "the_one", "2000", "2k", "01", "02", "03", "04", "05", "06", "07", "08", "09");
        $separator = array("", "-", "_");

        for ($k = 10; $k <= 99; $k++) {
            $list[] = $k;
        }

        shuffle($list);

        $i = 0;
        $results = '<div id="theresults">';
        foreach ($list as $item) {
            shuffle($separator);
            if ($i >= 6) break;
            $i++;
            $results .= '<a onclick="addNickname(\'' . $this->request->query . $separator[0] . $item . '\');" class="box_screenname" href="javascript:;">' . $this->request->query . $separator[0] . $item . '</a>';
        }
        $results .= "<br><br></div>";
        echo json_encode(array('query' => $this->request->query, 'suggestions' => $results));
    }

    private function checkScreenName()
    {

        $this->load('model');
        $check = $this->model->checkScreenName($this->request->screen_name, ($this->request->id ? $this->request->id : null));
        echo $check->id ? 'false' : 'true';
    }

    private function checkNickName()
    {

        $this->load('user');
        $check = $this->user->checkNickName($this->request->display_name, ($this->request->id ? $this->request->id : null));
        echo $check->id ? 'false' : 'true';
    }

    private function checkUniqueEmail()
    {

        $this->load('model');
        $check = $this->model->checkUniqueEmail($this->request->email, ($this->request->id ? $this->request->id : null));
        echo $check->id ? 'false' : 'true';
    }

    private function checkUserName()
    {
        $this->load('user');
        $check = $this->user->checkUniqueUsername($this->request->username);
        echo $check->id ? 'false' : 'true';
    }

    private function checkUserUniqueEmail()
    {
        $this->load('user');
        $check = $this->user->checkUniqueEmail($this->request->email, ($this->request->id ? $this->request->id : null));
        echo $check->id ? 'false' : 'true';
    }

    private function checkModeratorUniqueEmail()
    {
        $this->load('moderator');
        $check = $this->moderator->checkUniqueEmail($this->request->email, ($this->request->id ? $this->request->id : null));
        echo $check->id ? 'false' : 'true';
    }

    private function checkModeratorUserName()
    {
        $this->load('moderator');
        $check = $this->moderator->checkUniqueUsername($this->request->username);
        echo $check->id ? 'false' : 'true';
    }

    public function checkCapchaContact()
    {

        $post = $this->_request->getPost();

        if (md5($post['captcha']) != $_SESSION['image_verification2']) {
            echo 'false';
        } else {
            echo 'true';
        }

    }

    public function imageverification2Action()
    {

        $alphanum = "abcdefghjkmnpqrstuvwxyz23456789";
        $rand = substr(str_shuffle($alphanum), 0, 6);
        $bgs = array("images/background3.jpg");

        // create an image object using the chosen background
        $image = imagecreatefromjpeg($bgs[array_rand($bgs)]);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        // write the code on the background image
        imagestring($image, 5, 3, 3, $rand, $textColor);

        $_SESSION['image_verification2'] = md5($rand);

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-type: image/png');

        // send the image to the browser
        imagepng($image);

        // destroy the image to free up the memory
        imagedestroy($image);
    }

    private function addFollow()
    {

        $this->load('followers');
        $response = $this->followers->addFollow($this->request->id_model, $_SESSION['user']['id'], $this->request->type);

        $this->load("notifications");
        $this->notifications->addNotification($_SESSION['user']['id'], "user", $this->request->id_model, "model", "model-follow", "add", 1, getUserIp());

        echo json_encode($response);
    }

    private function removeFollow()
    {
        $this->load('followers');
        $response = $this->followers->removeFollow($this->request->id_model, $_SESSION['user']['id'], $this->request->type);

        $this->load("notifications");
        $this->notifications->addNotification($_SESSION['user']['id'], "user", $this->request->id_model, "model", "model-follow", "remove", 1, getUserIp());

        echo json_encode($response);
    }

    private function setGalleryCover()
    {

        if (!$this->request->p_id) die('error, invalid data.');

        $this->load('albums');
        $this->load('photos');

        $this->_data['photo'] = $this->photos->getPhotoById($this->request->p_id);

        if (!$this->_data['photo']->reference_id) die('album missing.');
        if (!Auth::isModel() || $_SESSION['user']['id'] != $this->_data['photo']->id_model || !$this->acl->isAllowed($_SESSION['group'], "model-manage-photos", "edit")) die('access denied.');

        //album update filename with the new cover
        $uploadFolder = config()->default_upload_dir;


        if ($this->albums->update(array("cover" => '/photos/' . $this->_data['photo']->filename), $this->db->quoteInto("id=?", $this->_data['photo']->reference_id))) {
            $response = 1;
        } else {
            $response = 0;
        }
        echo json_encode($response);
    }

    private function deletePhoto()
    {

        if (!$this->request->p_id) die('error, invalid data.');

        $this->load('albums');
        $this->load('photos');

        $response = 0;

        $this->_data['photo'] = $this->photos->getPhotoById($this->request->p_id);

        if (!$this->_data['photo']->reference_id) die('album missing.');
        if (!Auth::isModel() || $_SESSION['user']['id'] != $this->_data['photo']->id_model || !$this->acl->isAllowed($_SESSION['group'], "model-manage-photos", "edit")) die('access denied.');

        $this->_data['album'] = $this->albums->getAlbum($this->_data['photo']->reference_id);
        if (!$this->_data['album']->id) die('missing album.');

        //delete photo...
        $this->photos->deletePhoto($this->_data['photo']->filename, 'photo');
        $this->photos->delete(db()->quoteInto("id=?", $this->_data['photo']->id));

        $cover = $this->_data['album']->cover == $this->_data['photo']->filename ? "" : $this->_data['album']->cover;
        $this->albums->update(array("cover" => $cover, "updated" => time(), "total_photos" => $this->_data['album']->total_photos - 1), $this->db->quoteInto("id=?", $this->_data['photo']->reference_id));

        $this->load("notifications");
        $this->notifications->addNotification(user()->id, $_SESSION['group'], $this->_data['album']->id_model, "model", "delete_photo", "delete photo from gallery " . $this->_data['album']->name . ".", 1, getUserIp());

        $response = 1;

        echo json_encode($response);
    }

    public function modelscheduleAction()
    {

        if (!$this->request->id_model) exit;
        $this->load('model_schedule');

        if (Auth::isModel()) $status = null;
        else $status = 1;

        $data = $this->model_schedule->getModelScheduleById($this->request->id_model, null, null, $status);

        echo json_encode($data);

        // { "date": "1314579600000", "type": "meeting", "title": "Test Last Year", "description": "Lorem Ipsum dolor set", "url": "http://www.event3.com/" }

    }

    public function getBlockRules()
    {

        $params = $this->_request->getParams();

        if (Auth::isModel()) {

            $this->load("model_block_access");
            $this->load("countries");
            $this->countries_list = $this->countries->fetchAll();
            $params['id_model'] = $_SESSION['user']['id'];
            if (isset($params['id_country'])) $params['id_country'] = $this->countries->getIdFromCode($params['id_country']);
            if (isset($params['city'])) $params['city'] = stripText($params['city']);
            if (isset($params['state'])) $params['state'] = stripText($params['state']);


            //p($params);
            $this->block_rules = $this->model_block_access->getAccessRules($params['id_model'], $params['id_country'], $params['state'], $params['city']);

            $content = '';

            $content .= '<table cellpadding="2" cellspacing="1" class="rules_list">';

            if (count($this->block_rules) > 0) {
                foreach ($this->block_rules as $rule) {
                    $cycle = $this->view->cycle(array("light", "dark"))->next();
                    $content .= '<tr class="' . $cycle . '">';
                    foreach ($this->countries_list as $c) {
                        if ($c->id == $rule->id_country) {
                            $content .= '<td>' . $c['name'] . "</td>";
                            break;
                        }

                    }
                    $content .= '<td>' . $rule->state . "</td>";
                    $content .= '<td>' . $rule->city . "</td>";
                    $content .= '<td>' . $rule->reason . "</td>";
                    $content .= '<td class="action">
                            <a href="' . $this->view->url(array("type" => 'general', "id" => $rule->id, "manage" => 'edit'), 'model-privacy-settings-manage') . '" title="Edit rule">
                                <img border="0" title="Edit rule" alt="Edit" src="/images/icons/edit_account.png">
                            </a>
                            <a href="' . $this->view->url(array("type" => 'general', "id" => $rule->id, "manage" => 'delete'), 'model-privacy-settings-manage') . '" title="Delete rule" onclick="return confirm(\'Delete rule?\')">
                                <img border="0" title="Delete rule" alt="Delete" src="/images/icons/delete.png">
                            </a>

                          </td>';
                    $content .= '</tr>';
                }
            }

            $content .= '</table>';
            echo json_encode(array("content" => $content));

        } else {
            echo json_encode(array("content" => false));
        }
    }

    public function modelStats()
    {

        $request = $this->getRequest();
        if ($request->getPost()) {
            $this->load("earning_stats");
            if ($request->getPost('model') && $request->getPost('model'))
                echo $this->earning_stats->fetchModelStats((int)$request->getPost('model'), (int)$request->getPost('showby'));
            return;
        }
    }

    public function revenueStats()
    {

        $request = $this->getRequest();
        if ($request->getPost()) {
            $this->load("earning_stats");
            if ($request->getPost('fromDate') && $request->getPost('toDate') && $request->getPost('showBy'))
                echo $this->earning_stats->fetchRevenueStats($request->getPost('fromDate'), $request->getPost('toDate'), $request->getPost('showBy'));
            return;
        }
    }

    public function revenueModelStats()
    {

        $request = $this->getRequest();
        if ($request->getPost()) {
            $this->load("earning_stats");
            if ($request->getPost('fromDate') && $request->getPost('toDate') && $request->getPost('showBy') && $request->getPost('model'))
                echo $this->earning_stats->fetchRevenueStats($request->getPost('fromDate'), $request->getPost('toDate'), $request->getPost('showBy'), $request->getPost('model'));
            return;
        }
    }

    /**
     * rating stars plugin
     *
     */
    public function rating()
    {

        $params = $this->_request->getParams();

        if (!$params["idBox"] || !$params["rate"]) return false;

        if (!$params["extra"]) $params["extra"] = "image";

        switch ($params["extra"]) {
            case "image":
            case "photo":
                $table = $this->load("photos");
                $_SESSION["rate"]["image"][] = (int)$params["idBox"];
                break;
            case "album":
            case "gallery":
                $table = $this->load("albums");
                $_SESSION["rate"]["gallery"][] = (int)$params["idBox"];
                break;
            case "video":
                $table = $this->load("video");
                $_SESSION["rate"]["video"][] = (int)$params["idBox"];
                break;
            case "model":
                $table = $this->load("model");
                $_SESSION["rate"]["model"][] = (int)$params["idBox"];
                break;
            case "post":
                $table = $this->load("blog_posts");
                $_SESSION["rate"]["post"][] = (int)$params["idBox"];
                break;
        }
        $table->update(array("votes" => new Zend_Db_Expr("votes+1"), "rating" => new Zend_Db_Expr("(rating+{$params["rate"]})/(votes)")), db()->quoteInto("id=?", (int)$params["idBox"]));
        echo json_encode($params["extra"]);
    }

    /**
     * like/dislike plugin
     *
     */
    public function likes()
    {

        $params = $this->_request->getParams();

        //p($params."");
        if (!isset($params["item"]) || !isset($params["item_type"]) || !isset($params["option"])) return false;

        $id = (int)$params["item"];
        $option = $params["option"];

        //check if item liked or disliked ??
        ($option) ? $like = 1 : $dislike = 0;

        // what to update ?
        ($like) ? $action = "likes" : $action = "dislikes";

        // echo $action."-".$like."-".$dislike;exit;
        $message = "";
        switch ($params["item_type"]) {
            case "image":
                $tableName = "photos";
                if (isset($_SESSION["likes"]["image"]) && in_array($id, $_SESSION["likes"]["image"])) {
                    $message = "is_voted";
                } else {
                    $_SESSION["likes"]["image"][] = $id;
                    $message = "ok";
                }
                break;
            case "gallery":
                $tableName = "albums";
                if (isset($_SESSION["likes"]["gallery"]) && in_array($id, $_SESSION["likes"]["gallery"])) {
                    $message = "is_voted";
                } else {
                    $_SESSION["likes"]["gallery"][] = $id;
                    $message = "ok";
                }
                break;
            case "video":
                $tableName = "video";
                if (isset($_SESSION["likes"]["video"]) && in_array($id, $_SESSION["likes"]["video"])) {
                    $message = "is_voted";
                } else {
                    $_SESSION["likes"]["video"][] = $id;
                    $message = "ok";
                }
                break;
            case "model":
                $tableName = "model";
                if (isset($_SESSION["likes"]["model"]) && in_array($id, $_SESSION["likes"]["model"])) {
                    $message = "is_voted";
                } else {
                    $_SESSION["likes"]["model"][] = $id;
                    $message = "ok";
                }
                break;
            case "post":
                $tableName = "blog_posts";
                if (isset($_SESSION["likes"]["post"]) && in_array($id, $_SESSION["likes"]["post"])) {
                    $message = "is_voted";
                } else {
                    $_SESSION["likes"]["post"][] = $id;
                    $message = "ok";
                }
                break;
            case "pledge":
                $tableName = "pledge";
                if (isset($_SESSION["likes"]["pledge"]) && in_array($id, $_SESSION["likes"]["pledge"])) {
                    $message = "is_voted";
                } else {
                    $_SESSION["likes"]["pledge"][] = $id;
                    $message = "ok";
                }
                break;
        }

        $table = $this->load($tableName);

        if ($message == "ok")
            $test = $table->update(array($action => new Zend_Db_Expr($action . "+1")), db()->quoteInto("id=?", $id));

        $select = $table->select()->from($tableName, array("likes" => "likes", "dislikes" => "dislikes"))->where(db()->quoteInto("id=?", $id))->limit(1);
//       p($select."",1);
//       p($params."");
        $row = $table->fetchRow($select);

        if ($row)
            $arr = $row->toArray();
        else
            $message = "error_not_found";

        $arr["like_status"] = $message;

        echo json_encode($arr);
        exit;
    }

    /**
     * view item function
     *
     */
    public function viewitem()
    {
        $params = $this->_request->getParams();

        if (!$params["id"] || !$params["type"]) return null;

        $id = (int)$params["id"];
        $status = null;

        if (isset($_SESSION["views"][$params["type"]][$params["id"]]))
            return false;
        else {
            switch ($params["type"]) {
                case "image":
                    $action = "total_views";
                    $table = $this->load("photos");
                    $status = "ok";
                    break;
                case "image":
                    break;
            }

            if ($status == "ok") {
                $table->update(array($action => new Zend_Db_Expr($action . "+1")), db()->quoteInto("id=?", $id));
                $_SESSION["views"][$params["type"]][$params["id"]] = $_SERVER["REMOTE_ADDR"];
                echo json_encode(array("status" => "ok"));
            } else {
                echo json_encode(array("status" => "fail"));
            }
        }
    }
    /**
     * pledge contribute
     *
     */

    /*   public function contribute(){

           $params = $this->_request->getParams();
           if(!isset($params["item"]) || !isset($params["amount"])  || !Auth::isLogged()) return false;

           $arr = array();

           $this->load("pledge");

           $pledge = $this->pledge->getById((int)$params["item"]);
           $end_date = ($pledge->duration_type == "days" ? ($pledge->start_date + ($pledge->duration_days * 3600*24)) : $pledge->duration);



           if(!Auth::isLogged()){
               $arr["status"] = "fail";
               $arr["message"] = "Login to contribute!";
           } elseif(is_numeric($params["amount"])) {
               if($_SESSION["user"]["chips"] >= $params["amount"]){
                   $this->load("pledge_funder");
                   $contribution = array(
                                           "id_user" => $_SESSION["user"]["id"],
                                           "user_type" => $_SESSION["group"],
                                           "amount" => $params["amount"],
                                           "id_pledge" => (int) $params["item"],
                                           "added" =>  time()
                                       );
                   $id = $this->pledge_funder->insert($contribution);
                   if($id) {
                       if(time() > $end_date) {
                           $arr["status"] = "fail";
                           $arr["message"] = "Pledge ended!";
                       } else {
                           $arr["status"] = "ok";
                           $user_chips = (int)($_SESSION["user"]["chips"] - $params["amount"]);
                           $arr["chips"] = user()->chips  = $_SESSION["user"]["chips"] = $user_chips;
                       }
                   } else{
                       $arr["status"] = "fail";
                       $arr["message"] = "Not added!";
                   }
               } else {
                   $arr["status"] = "fail";
                   $arr["message"] = "Not enough chips!";
               }
           } else {
               $arr["status"] = "fail";
               $arr["message"] = "Amount not number!";
           }

           echo json_encode($arr);
       }*/


    /**
     * pledge perk purchase
     *
     */

    public function purchasePerk()
    {

        $params = $this->_request->getParams();

        if (!isset($params["pledge"]) || !Auth::isLogged()) return false;

        $arr = array();
        if (!Auth::isLogged()) {
            $arr["status"] = "fail";
            $arr["message"] = "Login to purchase!";
        } else {

            $amount = 0;
            if (isset($params["amount"]) && (int)$params["amount"] > 0) {
                $amount = (int)$params["amount"];
            } else {
                if (isset($this->params["pledge"])) {
                    $this->load("pledge_perk");
                    $perk = $this->pledge_perk->getById((int)$this->params["item"], (int)$this->params["pledge"]);
                    if ($perk)
                        $amount = $perk->amount;
                }
            }


            $this->load("pledge");
            $pledge = $this->pledge->getById((int)$params["pledge"]);

            if ($pledge) {

                if ($amount > 0) {
                    $end_date = $pledge->end_date;

                    if (time() <= $end_date) {
                        $sm = Zend_Registry::get('service_manager');
                        $wallet = $sm->get('wallet');
                        if ($wallet->getAmount() >= $amount) {
                            $this->load("pledge_funder");
                            $id = $this->pledge_funder->insert(array("id_user" => $_SESSION["user"]["id"],
                                "amount" => $amount,
                                "reference_id" => (int)$params["pledge"],
                                "entity" => Application\Entity\PledgeFunder::class,
                                "id_perk" => ($perk->id ? (int)$perk->id : ""),
                                "date" => (new \DateTime())->format('Y-m-d H:i:s'),
                                "anonymous" => $params["anonymous"] ? 1 : 0,
                            ));
                            if ($id) {
                                $wallet->contribute($amount);
                                $arr["status"] = "ok";
                                $arr["chips"] = $wallet->getAmount();
                            } else {
                                $arr["status"] = "fail";
                                $arr["message"] = "Not added!";
                            }
                        } else {
                            $arr["status"] = "fail";
                            $arr["message"] = "Not enough chips!";
                        }
                    } else {
                        $arr["status"] = "fail";
                        $arr["message"] = "Pledge expired";
                    }
                } else {
                    $arr["status"] = "fail";
                    $arr["message"] = "Insert amount!";
                }
            } else {
                $arr["status"] = "fail";
                $arr["message"] = "Not found!";
            }

        }
        echo json_encode($arr);
    }

    /**
     * @return bool
     * @throws Zend_Exception
     */
    public function editReview()
    {
        $params = $this->_request->getParams();
        if (!isset($params["type"]) || !isset($params["id"])) return;

        $this->load("reviews");

        switch ($params["type"]) {
            case "delete":
                $this->reviews->delete("id=" . (int)$params["id"]);
                return true;
                break;
            case "edit":
                if (!isset($params["review"])) return false;
                $this->reviews->update(array("review" => $params["review"], "active" => $params['active']), "id=" . (int)$params["id"]);
                return true;
                break;
            case "default":
                return;
                break;
        }
    }

    public function modelsOrder()
    {
        $post = $this->_request->getPost();
        if ($post) {
            p($post);
            $display_order = $post["modelpositions"];

            $ids = implode(',', array_values($display_order));
            $sql = "UPDATE user SET display_order = CASE id ";
            foreach ($display_order as $ordinal => $id) {
                $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
            }
            $sql .= "END WHERE id IN ($ids)";

            db()->query($sql);
        }
    }

    public function photoalbum()
    {
        $params = $this->_request->getParams();
        $this->load("albums");
        $photos = $this->albums->getCarouselPhotos($params["album"]);
        $ph = array();
        foreach ($photos as $photo) {
            $ph[] = '/uploads/photos/' . $photo->filename;
        }
        $ret["status"] = "ok";
        $ret["photos"] = $ph;
        echo json_encode($ret);
    }

    public function reportcontent()
    {
        $params = $this->_request->getParams();

        $id_moderator = 0;

        if (Auth::isLogged()) {
            $this->load("model");
            switch ($params["content_type"]) {
                case "image":
                case "photo":
                    $this->load("photos");
                    $content = $this->photos->getPhotoById((int)$params["id_content"]);
                    if (!$content->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $id_moderator = $content->id_moderator;
                    $id_model = $content->id_model;
                    break;
                case "video":
                    $this->load("video");
                    $content = $this->video->getVideoById((int)$params["id_content"]);
                    if (!$content->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $id_moderator = $content->getIdModerator($this->load("model_moderator"));
                    $id_model = $content->id_model;
                    break;
                case "blog":
                    $this->load("blog_posts");
                    $content = $this->blog_posts->getById((int)$params["id_content"]);
                    if (!$content->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $id_moderator = $content->getIdModerator($this->load("model_moderator"));
                    $id_model = $content->id_model;
                    break;
                case "model":
                    $this->load("model");
                    $content = $this->model->getModelById((int)$params["id_content"]);
                    if (!$content->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $id_moderator = $content->assigned_to;
                    $id_model = $content->id;
                    break;
                case "pledge":
                    $this->load("pledge");
                    $content = $this->pledge->getById((int)$params["id_content"]);
                    if (!$content->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $id_moderator = $content->id_moderator;
                    $id_model = $content->id_model;
                    break;
                case "perk":
                    $this->load("pledge_perk");
                    $perk = $this->pledge_perk->getById((int)$params["id_content"]);
                    if (!$perk->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $this->load("pledge");
                    $content = $this->pledge->getById($perk->id);
                    if (!$content->id) {
                        echo json_encode(array("message" => "Content not found", "status" => "fail"));
                        exit;
                    }
                    $id_moderator = $content->id_moderator;
                    $id_model = $content->id_model;
                    break;
            }
            $addNotification = array(
                "id_from" => $_SESSION['user']['id'],
                "type_from" => $_SESSION['group'],
                "id_to" => $id_moderator,
                "type_to" => "moderator",
                "type" => "report_" . $params["content_type"],
                "notification" => ucfirst($params["reported_for"]) . ' ' . $params["content_type"] . ' | reported by ' . (user()->username ? user()->username : (user()->scren_name ? user()->username : "")) . '. Reason: ' . $params["report_reason"],
                "ip" => $_SERVER["REMOTE_ADDR"],
                "date" => time(),
                "resource" => $params["id_content"],
                "linked_resource" => $id_model,
            );

            if ($id_moderator != 0)
                $this->addNotification($addNotification, "admin");

            $this->addNotification($addNotification, "moderator");

            $this->load("notifications");
            $this->notifications->addNotification($_SESSION['user']['id'], $_SESSION['group'], 0, "moderator", "report", "Report " . $params["content_type"] . ': ' . $params["report_reason"], 1, getUserIp());

            $response["message"] = "Content reported. Request will be reviewed!";
            $response["status"] = "success";
        } else {
            $respose["message"] = "Must be logged in to report";
            $respose["status"] = "fail";
        }

        echo json_encode($response);
    }

    public function photoCaption()
    {
        $params = $this->_request->getParams();
        if (isset($params["pid"])) {

            $ret = array();

            if (Auth::isModel() || Auth::isModerator()) {
                $this->load("photos");

                $update = $this->photos->update(array("caption" => $params["caption"]), "id=" . (int)$params["pid"] .
                                                                                      (Auth::isModel() ? " AND user=" .user()->id : ''));

                if ($update) {
                    $ret["status"] = "success";
                } else {
                    $ret["status"] = "fail";
                }
            }


        } else {
            $ret["status"] = "fail";
            $ret["message"] = "No ID";
        }

        echo json_encode($ret);
    }

}