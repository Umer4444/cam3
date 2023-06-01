<?php
use \Zend\Crypt\Password\Bcrypt;

/**
 * Class IndexController
 */
class IndexController extends App_Controller_Action
{
    /**
     * @throws Zend_Exception
     */
    public function init()
    {
        parent::init();

        if ($this->_request->id_model)
            $id_model = $this->_request->id_model;
        elseif (isset($_SESSION["website"]["id_model"]))
            $id_model = $_SESSION["website"]["id_model"];
        else
            $id_model = false;

        $this->_data["id_model"] = $id_model;
        $this->_data["params"] = $this->_request;
        // p($this->_data["params"]);

        if (Auth::isUser()) {

            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
            //p($this->_data['unread_count']->toArray(),1);
            unset($this->messages);

            $this->load("user_notifications");
            //$this->_data["notification_count"] = $this->user_notifications->getUnreadCount("model", $_SESSION["user"]["id"]);
            $this->_data["notification_count"] = $this->user_notifications->getNewNotificationCount("user", $_SESSION["user"]["id"], $_SESSION["user"]["last_notification"], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
            unset($this->user_notifications);
            //p($this->_data["notification_count"]);
        }

    }

    /**
     * used for redirecting to zf2 login
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function redirectToLoginAction()
    {
        $this->_redirect('/account/login');
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function filtermodelsAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-filtermodels", "view")) {
            $this->_redirect("/404");
        }

        $this->load('model');
        $this->load('categories');
        $this->load('info');

        $this->_data['categories'] = $this->categories->getCategoriesArray("model");
        $this->_data["info"] = $this->info->getArray();

        $this->_data["maxValues"] = $this->model->getMaxValues();

        $nr = 10;
        $post = $this->params;
        $this->_data["filter"] = array();
        /* filter model categories */
        $this->_data["filter"]["categories"] = null;
        $this->_data["filter"]["categories_id"] = array();
        if (isset($post["categories"])) {
            $arr_cat = explode(",", trim($post["categories"], ","));

            foreach ($arr_cat as $ctg) {
                if (array_search(str_replace("_", " ", $ctg), $this->_data['categories']))
                    $this->_data["filter"]["categories"] .= array_search(str_replace("_", " ", $ctg), $this->_data['categories']) . ",";
            }
        }

        if (isset($this->_data["filter"]["categories"]))
            $this->_data["filter"]["categories_id"] = explode(",", $this->_data["filter"]["categories"]);
        else
            $this->_data["filter"]["categories_id"] = array();

        $this->_data["filter"]["categories_id"] = array_filter($this->_data["filter"]["categories_id"]);

        /* filter hair type */
        $this->_data["filter"]["hair_type"] = "";
        $this->_data["filter"]["hair_type_id"] = array();
        if (isset($post["hair_type"])) {
            $this->_data["filter"]["hair_type"] = str_replace("_", " ", $post["hair_type"]);
            $this->_data["filter"]["hair_type_id"] = explode(",", $post["hair_type"]);
        }
        $this->_data["filter"]["hair_type_id"] = array_filter($this->_data["filter"]["hair_type_id"]);

        /* filter color eye */
        $this->_data["filter"]["eye_color"] = "";
        $this->_data["filter"]["eye_color_id"] = array();
        if (isset($post["eye_color"])) {
            $this->_data["filter"]["eye_color"] = str_replace("_", " ", $post["eye_color"]);
            $this->_data["filter"]["eye_color_id"] = explode(",", $post["eye_color"]);
        }
        $this->_data["filter"]["eye_color_id"] = array_filter($this->_data["filter"]["eye_color_id"]);

        /* filter language */
        $this->_data["filter"]["languages"] = "";
        $this->_data["filter"]["languages_id"] = array();
        if (isset($post["languages"])) {
            $this->_data["filter"]["languages"] = str_replace("_", " ", $post["languages"]);
            $this->_data["filter"]["languages_id"] = explode(",", $post["languages"]);
        }
        $this->_data["filter"]["languages_id"] = array_filter($this->_data["filter"]["languages_id"]);

        /* filter orientation */
        $this->_data["filter"]["orientation"] = "";
        $this->_data["filter"]["orientation_id"] = array();
        if (isset($post["orientation"])) {
            $this->_data["filter"]["orientation"] = str_replace("_", " ", $post["orientation"]);
            $this->_data["filter"]["orientation_id"] = explode(",", $post["orientation"]);
        }
        $this->_data["filter"]["orientation_id"] = array_filter($this->_data["filter"]["orientation_id"]);

        /* filter gender */
        $this->_data["filter"]["gender"] = "";
        $this->_data["filter"]["gender_id"] = array();
        if (isset($post["gender"])) {
            $this->_data["filter"]["gender"] = str_replace("_", " ", $post["gender"]);
            $this->_data["filter"]["gender_id"] = explode(",", $post["gender"]);
        }
        $this->_data["filter"]["gender_id"] = array_filter($this->_data["filter"]["gender_id"]);

        /* filter age */
        $this->_data["filter"]["age"] = "";
        $this->_data["filter"]["age_id"] = array();
        if (isset($post["age"])) {
            $this->_data["filter"]["age"] = str_replace("_", " ", $post["age"]);
            $this->_data["filter"]["age_id"] = explode(",", $post["age"]);
        }
        $this->_data["filter"]["age_id"] = array_filter($this->_data["filter"]["age_id"]);

        /* weight age */
        $this->_data["filter"]["weight"] = "";
        $this->_data["filter"]["weight_id"] = array();
        if (isset($post["weight"])) {
            $this->_data["filter"]["weight"] = str_replace("_", " ", $post["weight"]);
            $this->_data["filter"]["weight_id"] = explode(",", $post["weight"]);
        }
        $this->_data["filter"]["weight_id"] = array_filter($this->_data["filter"]["weight_id"]);

        if (isset($post['hidden'])) {

            $this->_data["filter"]["hidden"] = true;
        }
        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        $this->_data["return_to"] = false;

        if (isset($post["on_action"]) && $post["on_action"] == "live")
            $is_live = true;
        else
            $is_live = false;
        if (isset($post['s'])) { //for search
            $models = $this->model->getModels(1, $post['s'], $is_live, $this->_data["filter"]);
        } else {
            $models = $this->model->getModels(1, null, $is_live, $this->_data["filter"]);
        }

        $paginator = Zend_Paginator::factory($models);
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $this->view->assign($this->_data);
        $this->render("models");

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function modelsAction()
    {

        $this->load('model');
        $this->load('categories');
        $this->load('info');

        $this->_data["return_to"] = "models";

        $this->_data['categories'] = $this->categories->getCategoriesArray();
        $this->_data["info"] = $this->info->getArray();

        $this->_data["maxValues"] = $this->model->getMaxValues();

        $nr = 12;
        $post = $this->params;

        $this->_data["filter"] = null;
        $this->_data["filter-ids"] = array();
        if (isset($post["categories"])) {
            $arr_cat = explode(",", trim($post["categories"], ","));

            foreach ($arr_cat as $ctg) {
                if (array_search(str_replace("_", " ", $ctg), $this->_data['categories']))
                    $this->_data["filter"] .= array_search(str_replace("_", " ", $ctg), $this->_data['categories']) . ",";
            }
        }
        if ($this->_data["filter"])
            $this->_data["filter_ids"] = explode(",", $this->_data["filter"]);
        else
            $this->_data["filter_ids"] = array();

        $this->_data["filter_ids"] = array_filter($this->_data["filter_ids"]);

        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        if (isset($post['s'])) { //for search
            $models = $this->model->getModelList(1, $post['s'], null, $this->_data["filter"]);
        } else {
            $models = $this->model->getModelList(1, null, null, $this->_data["filter"]);
        }

        $paginator = Zend_Paginator::factory($models);
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function liveAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-live", "view")) $this->_redirect("/404");

        $this->load('model');
        $this->load('categories');
        $this->load('info');

        $this->_data["return_to"] = "live";

        $this->_data['categories'] = $this->categories->getCategoriesArray();

        $this->_data["info"] = $this->info->getArray();

        $nr = 10;
        $post = $this->params;

        $this->_data["maxValues"] = $this->model->getMaxValues();

        $this->_data["filter"] = null;
        $this->_data["filter-ids"] = array();

        if (isset($post["filter-categories"])) {
            $arr_cat = explode(",", trim($post["filter-categories"], ","));

            foreach ($arr_cat as $ctg) {
                if (array_search(str_replace("_", " ", $ctg), $this->_data['categories']))
                    $this->_data["filter"] .= array_search(str_replace("_", " ", $ctg), $this->_data['categories']) . ",";
            }
        }
        if ($this->_data["filter"])
            $this->_data["filter_ids"] = explode(",", $this->_data["filter"]);
        else
            $this->_data["filter_ids"] = array();

        $this->_data["filter_ids"] = array_filter($this->_data["filter_ids"]);


        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        if (!isset($filter)) {
            $filter = '';
        }
        if ($post['s']) { //for search
            $models = $this->model->getModels(1, $post['s'], 1, $filter);
        } else {
            $models = $this->model->getModels(1, null, 1, $filter);
        }

        $paginator = Zend_Paginator::factory($models);
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $this->view->assign($this->_data);
        $this->render("live");
    }

    /**
     * @throws Zend_Exception
     */
    public function watchmodelAction()
    {

        /*$serviceLocator = Zend_Registry::get('service_manager');
        $url = $serviceLocator->get('viewhelpermanager')->get('url');
        var_dump($url('home'));die();*/

        if (!$this->acl->isAllowed("theme-website", "theme-watchmodel", "view")) $this->_redirect("/404");
        $post = $this->params;

        unset($_SESSION['user_chat']['logout_message']);

        $this->_data['rtmp'] = config()->rtmp;

        if (!user()->display_name && Auth::isLogged()) {
            $this->_data['message'] = "Please fill out your display_name! You will be logged in with your username until you fill out your nickname";
        } else {
            $this->_data['message'] = "";
        }

        $this->load("model");
        //$this->_data['model'] = $this->model->fetchRow($this->model->select()->from("model")->where("id=?", $this->_request->id));
        $this->_data['model'] = $this->model->getModel($this->_data["id_model"], true);

        //no such model - redirect to error page
        if (!$this->_data['model']->id || $this->_data['model']->active != 1) $this->_redirect('/404/');

        //check to see if chat is opened in pop up an load partial view
        if ($post["popup"]) {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer('watch-popup');
        } else {
            if (isset($_COOKIE["popup_" . $this->_data['model']->id])) {

                $this->view->assign($this->_data);
                $this->_helper->viewRenderer('watch-other-window');

                $this->render();
                return;
            }
        }

        $this->load("webchat_sessions");
        $webchatSession = $this->webchat_sessions->getSession($this->_data['model']->id);

        if ($webchatSession) {
            $this->_data["model_cams"] = $webchatSession->cameras;
        } else {
            $this->_data["model_cams"] = 1;
        }

        if (isset($this->_request->nav) && isset($this->_request->position)) {
            $nav_model = $this->model->navigateModel($this->_request);

            if ($nav_model) {
                $this->_redirect($this->view->url(array("id_model" => $nav_model->id, "name" => $nav_model->screen_name), "watch"));
            } else {
                $this->_helper->FlashMessenger->addMessage(notice("No " . $this->_request->nav . " model"));
                $this->_redirect($this->view->url(array("id_model" => $this->_data["model"]->id, "name" => $this->_data["model"]->screen_name), "watch"));
            }
        }

        $this->load("chat");
        $this->load("user_favorites");
        $this->load("webchat_users");
        $this->load('video');
        $this->load("user_settings");

        //if model offline / spy mode - show model videos
        $this->_data['videos_for_sale'] = $this->video->getVideos($this->_data['model']->id, 'vod', null, 'id desc', 8);

        //get model status - is online/offline
        $model_status = $this->webchat_users->hasModel($this->_data['model']['id']);
        if ($model_status > 0) $this->_data['model_status'] = 'online';
        else $this->_data['model_status'] = 'offline';


        //not allow users to enter watch model if they are banned
        $this->load("model_user_access");
        if (isset($_SESSION['user_chat']))
            $user_id = $_SESSION['user_chat'][$this->_data['model']->id]['id'];
        else
            $user_id = null;

        $restiction = $this->model_user_access->getRestrictedWeb($this->_data['model']->id, $user_id, $_SERVER["REMOTE_ADDR"]);
        if (Auth::isUser() && in_array($user_id, $restiction['user'])) {
            unset($_SESSION['user_chat']['logout_time']);
            //$this->_redirect('/live/');
            $this->_data['model_status'] = 'banned';
            $this->_data['banned_message'] = "Free preview time expired. "; //"Go private with this model, buy chips if you don't have enough.";
        } elseif (!Auth::isLogged() && in_array($_SERVER["REMOTE_ADDR"], $restiction['ip'])) {
            unset($_SESSION['user_chat']['logout_time']);
            //$this->_redirect('/live/');
            $this->_data['model_status'] = 'banned';
            $this->_data['banned_message'] = "Free preview time expired. Login for more.";
        }

        //get user sounds if any
        $sounds = $this->user_settings->getFieldsByUser(user()->id, "user", "sounds");
        if ($sounds) $sounds = $sounds->toArray();

        $sounds_list = array();
        foreach ($sounds as $sound) {
            $sounds_list[$sound['type']] =  $sound['value'];
        }


        $this->load("model_rates");
        $this->_data["show_rates"] = $this->model_rates->getModelRateByType($this->_data['model']->id, $this->_data['model']->chat_type . "_mode");
        $this->_data["model_rates"] = $this->model_rates->getRatesByModel($this->_data['model']->id);


        $this->_data['sounds'] = json_encode($sounds_list);

        //get number of users on chat
        $this->_data["counter_chat_users"] = $this->webchat_users->countUsers($this->_data['model']->id);
        if ($this->_data["counter_chat_users"] > 0) $this->_data["counter_chat_users"] -= 1;

        // get maxim users allowed in group
        $this->_data["max_group_users"] = config()->max_group_users;

        if (Auth::isLogged()) {
            //get model favorite status
            $is_favorite = $this->user_favorites->fetchRow($this->user_favorites->select()->from("user_favorites")->where("id_user=?", user()->id)->where("id_model=?", $this->_data['model']->id));
            if (count($is_favorite) == 1) $this->_data['favorite'] = true;
            else $this->_data['favorite'] = false;
            //exit;


            if (Auth::isUser() && $this->_data['model_status'] == 'online') {

                //if chat type show
                if ($this->getRequest()->isPost()) {
                    $post = $this->_request->getPost();

                    //login user to show
                    if ($this->_data["model"]->chat_type == "show" && isset($post["fee_agree"])) {
                        $chatName = (

                        user()->screen_name ?
                            user()->screen_name : (
                        user()->display_name ? user()->display_name :
                            (user()->username ? user()->username : 'performer' . user()->id)
                        )
                        );
                        $this->chat->login(
                            $chatName,
                            user()->email,
                            $this->_data['model']->id,
                            "user_" . user()->id,
                            $this->_data['model']->chat_type,
                            null,
                            null,
                            user()->chat_font
                        );
                    }

                    //login user to group
                    if ($this->_data["model"]->chat_type == "group" && isset($post["join_group"]) && $this->_data["counter_chat_users"] < config()->max_group_users) {
                        $chatName = (

                        user()->screen_name ?
                            user()->screen_name : (
                        user()->display_name ? user()->display_name :
                            (user()->username ? user()->username : 'performer' . user()->id)
                        )
                        );

                        $this->chat->login(
                            $chatName,
                            user()->email,
                            $this->_data['model']->id,
                            "user_" . user()->id,
                            $this->_data['model']->chat_type,
                            null,
                            null,
                            user()->chat_font);
                    }
                }

                //autologin chat
                $logged = $this->chat->checkLogged($this->_data['model']->id);

                $this->load("webchat_sessions");
                $webchatSession = $this->webchat_sessions->getSession($this->_data['model']->id, user()->id);


                if (
                    ($logged['logged'] && $this->_data['model']->chat_type == $logged['loggedAs']['chat_type'])
                    || (!$logged['logged'] && $this->_data['model']->chat_type == 'normal')
                    || (!$logged['logged'] && $webchatSession)
                ) {

                    $chatName = (

                    user()->screen_name ?
                        user()->screen_name : (
                    user()->display_name ? user()->display_name :
                        (user()->username ? user()->username : 'performer' . user()->id)
                    )
                    );
                    $this->chat->login(
                        $chatName,
                        user()->email,
                        $this->_data['model']->id,
                        "user_" . user()->id,
                        $this->_data['model']->chat_type,
                        null,
                        null,
                        user()->chat_font
                    );
                } else {


                    if ($this->_data['model']->chat_type != 'private' || $_SESSION["user_chat"][$this->_data['model']->id]["chat_type"] != "spy") {
                        //chat - logout
                        db()->query("DELETE FROM webchat_users WHERE
                                    " . db()->quoteInto("id_user=?", "user_" . user()->id) . " AND
                                    " . db()->quoteInto("id_model=?", $this->_data['model']->id)
                        )->execute();

                        if ($_SESSION['group'] == 'user') { //delete pending requests

                            db()->query("DELETE FROM model_requests WHERE
                                        " . db()->quoteInto("id_user=?", $_SESSION['user']['id']) . " AND
                                        " . db()->quoteInto("id_model=?", $this->_data['model']->id)
                            )->execute();

                        }

                        unset($_SESSION["user_chat"][$this->_data['model']->id]);
                    }

                }

            }

        }


        if (Auth::isUser()
            && (($_SESSION['user_chat'][$this->_data['model']->id]['chat_type'] == $this->_data['model']->chat_type && $this->_data['model']->chat_type == 'private')
                || ($_SESSION['user_chat'][$this->_data['model']->id]['chat_type'] == 'spy' && $this->_data['model']->chat_type == 'private')
                || $this->_data['model']->chat_type == 'vip'
                || $this->_data['model']->chat_type == 'group'
                // && $this->_data['model']->chat_type == 'show'
            )
        ) {


            if (($_SESSION['user_chat'][$this->_data['model']->id]['chat_type'] == 'spy' && ($this->_data['model']->chat_type == 'private'))
                || $this->_data['model']->chat_type == 'group'
                || $this->_data['model']->chat_type == 'show'
                //&& $this->_data['model']->chat_type == 'show'
            ) {

                //$id_user = $this->webchat_users->getPrivateUserId($this->_data['model']->id);

                $webchatSession = $this->webchat_sessions->getSession($this->_data['model']->id);

                $id_user = $webchatSession->id_user;

                $this->_data['stream'] = $this->_data['model']->getStream($id_user);
                for ($i = 1; $i <= $this->_data["model_cams"]; $i++) {
                    $this->_data["model_streams"][$i] = $this->_data["model"]->getStream($id_user, $i);
                }


            } else {

                $this->_data['stream'] = $this->_data['model']->getStream(user()->id);
                for ($i = 1; $i <= $this->_data["model_cams"]; $i++) {
                    $this->_data["model_streams"][$i] = $this->_data["model"]->getStream(user()->id, $i);
                }
            }
        } else {

            $this->_data['stream'] = $this->_data['model']->getStream();
            for ($i = 1; $i <= $this->_data["model_cams"]; $i++) {
                $this->_data["model_streams"][$i] = $this->_data["model"]->getStream(null, $i);
            }
        }

        /*        //include APPLICATION_PATH."/../public/flashservices/services/PeerToPeer.php";
                $_SESSION['stream'] = $this->_data['stream'];

                $channelName = "model_".$this->_data['model']->id;
                $_SESSION['channel'] = $channelName;

                //$rtmp = "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/".$channelName;
                $rtmp = config()->rtmp;
                $_SESSION['rtmp'] = $rtmp;
                $this->_data['rtmp'] = $rtmp;*/

        /*
        $channel = array(
            "channel" => $channelName,
            "password" => "PASSWORD",
            "stream" => $_SESSION['stream'],
            "rtmfp" => "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/"
        );

        $_SESSION['new_channel'] = $channel;
        */

        //getUsers + getChats
        $users = $this->chat->getUsers($this->_data['model']->id, $this->view);
        $users_list = $users['users'];
        //sort users
        usort($users_list, "cmp_webchat_users");
        $users['users'] = $users_list;

        if (isset($_COOKIE["reset_counter"]) && $_COOKIE["reset_counter"] == true) {

            //update timer user
            $this->load('webchat_users');
            $this->webchat_users->updateLogged($this->_data['model']->id, user()->id);
            unset($_COOKIE["reset_counter"]);
            setcookie("reset_counter", "false", time() - 3600);

        }


        $chats = $this->chat->getChats($this->_data['model']->id);
        $loggedin = $this->webchat_users->getLogged($this->_data['model']->id, user()->id);

        if ($loggedin) {
            $this->_data['timer'] = time() - $loggedin->loggedin;
        } else {
            $loggedin = 0;
            $this->_data['timer'] = 0;
        }
        $this->_data['post_init'] = json_encode(array("getUsers" => $users, "getChats" => $chats));


        // user broad cast in private mode
        // if chat type private allow user to use webcam
        if (Auth::isUser() && $this->_data['model']->chat_type == 'private') {

            $this->load("user");
            $this->_data["user"] = $user = $this->user->getUserById(user()->id);
            unset($_SESSION['streams']["user"]);
            $_SESSION['streams']["user"][] = $user->getStream(user()->id);

            $rtmp = config()->rtmp;
            $_SESSION['rtmp'] = $rtmp;

            $this->_data['rtmp'] = $rtmp;
        }

        //get view helper
        $this->_data['performer'] = Zend_Registry::get('service_manager')->get('em')
            ->getRepository('Application\Entity\User')->find($this->_data["model"]->id);

    }

    public function watchsoloAction()
    {

        $post = $this->params;
        $this->_data["id"] = $post["id"];

        $this->getHelper('layout')->disableLayout();
        // $this->getHelper('viewRenderer')->setNoRender();

//        if (!$this->acl->isAllowed("theme-website", "theme-watchmodel", "view")) $this->_redirect("/404");

        unset($_SESSION['user_chat']['logout_message']);

        $this->_data['rtmp'] = config()->rtmp;

        if (!user()->display_name && Auth::isLogged()) {
            $this->_data['message'] = "Please fill out your display_name! You will be logged in with your username until you fill out your nickname";
        } else {
            $this->_data['message'] = "";
        }


        $this->load("model");
        //$this->_data['model'] = $this->model->fetchRow($this->model->select()->from("model")->where("id=?", $this->_request->id));

//        $this->_data['model'] = $this->model->getModel($this->$post['id_model'], true); //RAZVAN (TM)
        $this->_data['model'] = $this->model->getModel($post['id'], true);

        //no such model - redirect to error page
        if (!$this->_data['model']->id) $this->_redirect('/404/');

        $this->load("webchat_sessions");
        $webchatSession = $this->webchat_sessions->getSession($this->_data['model']->id);

        if ($webchatSession) {
            $this->_data["model_cams"] = $webchatSession->cameras;
        } else {
            $this->_data["model_cams"] = 1;
        }

        if (isset($this->_request->nav) && isset($this->_request->position)) {
            $nav_model = $this->model->navigateModel($this->_request);

            if ($nav_model) {
                $this->_redirect($this->view->url(array("id" => $nav_model->id, "name" => $nav_model->screen_name), "watch"));
            } else {
                $this->_helper->FlashMessenger->addMessage(notice("No " . $this->_request->nav . " model"));
                $this->_redirect($this->view->url(array("id" => $this->_data["model"]->id, "name" => $this->_data["model"]->screen_name), "watch"));
            }
        }

        $this->load("chat");
        $this->load("user_favorites");
        $this->load("webchat_users");
        $this->load('video');
        $this->load("user_settings");

        //if model offline / spy mode - show model videos
        $this->_data['videos_for_sale'] = $this->video->getVideos($this->_data['model']->id, 'vod', null, 'id desc', 8);

        //get model status - is online/offline
        $model_status = $this->webchat_users->hasModel($this->_data['model']['id']);
        if ($model_status > 0) $this->_data['model_status'] = 'online';
        else $this->_data['model_status'] = 'offline';

        //not allow users to enter watch model if they are banned
        $this->load("model_user_access");
        if (isset($_SESSION['user_chat']))
            $user_id = $_SESSION['user_chat'][$this->_data['model']->id]['id'];
        else
            $user_id = null;

        $restiction = $this->model_user_access->getRestrictedWeb($this->_data['model']->id, $user_id, $_SERVER["REMOTE_ADDR"]);
        if (Auth::isUser() && in_array($user_id, $restiction['user'])) {
            unset($_SESSION['user_chat']['logout_time']);
            //$this->_redirect('/live/');
            $this->_data['model_status'] = 'banned';
            $this->_data['banned_message'] = "Free preview time expired. "; //"Go private with this model, buy chips if you don't have enough.";
        } elseif (!Auth::isLogged() && in_array($_SERVER["REMOTE_ADDR"], $restiction['ip'])) {
            unset($_SESSION['user_chat']['logout_time']);
            //$this->_redirect('/live/');
            $this->_data['model_status'] = 'banned';
            $this->_data['banned_message'] = "Free preview time expired. Login for more.";
        }

        //get user sounds if any
        $sounds = $this->user_settings->getFieldsByUser(user()->id, "user", "sounds");
        if ($sounds) $sounds = $sounds->toArray();

        $sounds_list = array();
        foreach ($sounds as $sound) {
            $sounds_list[$sound['type']] =  $sound['value'];
        }


        $this->load("model_rates");
        $this->_data["show_rates"] = $this->model_rates->getModelRateByType($this->_data['model']->id, $this->_data['model']->chat_type . "_mode");
        $this->_data["model_rates"] = $this->model_rates->getRatesByModel($this->_data['model']->id);


        $this->_data['sounds'] = json_encode($sounds_list);

        //get number of users on chat
        $this->_data["counter_chat_users"] = $this->webchat_users->countUsers($this->_data['model']->id);
        if ($this->_data["counter_chat_users"] > 0) $this->_data["counter_chat_users"] -= 1;

        // get maxim users allowed in group
        $this->_data["max_group_users"] = config()->max_group_users;

        if (Auth::isLogged()) {
            //get model favorite status
            $is_favorite = $this->user_favorites->fetchRow($this->user_favorites->select()->from("user_favorites")->where("id_user=?", user()->id)->where("id=?", $this->_data['model']->id));
            if (count($is_favorite) == 1) $this->_data['favorite'] = true;
            else $this->_data['favorite'] = false;
            //exit;


            if (Auth::isUser() && $this->_data['model_status'] == 'online') {

                //if chat type show
                if ($this->getRequest()->isPost()) {
                    $post = $this->_request->getPost();

                    //login user to show
                    if ($this->_data["model"]->chat_type == "show" && isset($post["fee_agree"])) {
                        $chatName = (

                        user()->screen_name ?
                            user()->screen_name : (
                        user()->display_name ? user()->display_name :
                            (user()->username ? user()->username : 'performer' . user()->id)
                        )
                        );

                        $this->chat->login(
                            $chatName,
                            user()->email, $this->_data['model']->id,
                            "user_" . user()->id,
                            $this->_data['model']->chat_type,
                            null,
                            null,
                            user()->chat_font
                        );
                    }

                    //login user to group
                    if ($this->_data["model"]->chat_type == "group" && isset($post["join_group"]) && $this->_data["counter_chat_users"] < config()->max_group_users) {

                        $chatName = (

                        user()->screen_name ?
                            user()->screen_name : (
                        user()->display_name ? user()->display_name :
                            (user()->username ? user()->username : 'performer' . user()->id)
                        )
                        );

                        $this->chat->login(
                            $chatName,
                            user()->email,
                            $this->_data['model']->id,
                            "user_" . user()->id,
                            $this->_data['model']->chat_type,
                            null,
                            null,
                            user()->chat_font
                        );
                    }
                }

                //autologin chat
                $logged = $this->chat->checkLogged($this->_data['model']->id);

                $this->load("webchat_sessions");
                $webchatSession = $this->webchat_sessions->getSession($this->_data['model']->id, user()->id);


                if (
                    ($logged['logged'] && $this->_data['model']->chat_type == $logged['loggedAs']['chat_type'])
                    || (!$logged['logged'] && $this->_data['model']->chat_type == 'normal')
                    || (!$logged['logged'] && $webchatSession)
                ) {
                    $chatName = (

                    user()->screen_name ?
                        user()->screen_name : (
                    user()->display_name ? user()->display_name :
                        (user()->username ? user()->username : 'performer' . user()->id)
                    )
                    );

                    $this->chat->login(
                        $chatName,
                        user()->email,
                        $this->_data['model']->id,
                        "user_" . user()->id,
                        $this->_data['model']->chat_type,
                        null,
                        null,
                        user()->chat_font
                    );
                } else {


                    if ($this->_data['model']->chat_type != 'private' || $_SESSION["user_chat"][$this->_data['model']->id]["chat_type"] != "spy") {
                        //chat - logout
                        db()->query("DELETE FROM webchat_users WHERE
                                    " . db()->quoteInto("id_user=?", "user_" . user()->id) . " AND
                                    " . db()->quoteInto("id=?", $this->_data['model']->id)
                        )->execute();

                        if ($_SESSION['group'] == 'user') { //delete pending requests

                            db()->query("DELETE FROM model_requests WHERE
                                        " . db()->quoteInto("id_user=?", $_SESSION['user']['id']) . " AND
                                        " . db()->quoteInto("id=?", $this->_data['model']->id)
                            )->execute();

                        }

                        unset($_SESSION["user_chat"][$this->_data['model']->id]);
                    }

                }

            }

        }


        if (Auth::isUser()
            && (($_SESSION['user_chat'][$this->_data['model']->id]['chat_type'] == $this->_data['model']->chat_type && $this->_data['model']->chat_type == 'private')
                || ($_SESSION['user_chat'][$this->_data['model']->id]['chat_type'] == 'spy' && $this->_data['model']->chat_type == 'private')
                || $this->_data['model']->chat_type == 'vip'
                || $this->_data['model']->chat_type == 'group'
                // && $this->_data['model']->chat_type == 'show'
            )
        ) {


            if (($_SESSION['user_chat'][$this->_data['model']->id]['chat_type'] == 'spy' && ($this->_data['model']->chat_type == 'private'))
                || $this->_data['model']->chat_type == 'group'
                || $this->_data['model']->chat_type == 'show'
                //&& $this->_data['model']->chat_type == 'show'
            ) {

                //$id_user = $this->webchat_users->getPrivateUserId($this->_data['model']->id);

                $webchatSession = $this->webchat_sessions->getSession($this->_data['model']->id);

                $id_user = $webchatSession->id_user;

                $this->_data['stream'] = $this->_data['model']->getStream($id_user);
                for ($i = 1; $i <= $this->_data["model_cams"]; $i++) {
                    $this->_data["model_streams"][$i] = $this->_data["model"]->getStream($id_user, $i);
                }


            } else {

                $this->_data['stream'] = $this->_data['model']->getStream(user()->id);
                for ($i = 1; $i <= $this->_data["model_cams"]; $i++) {
                    $this->_data["model_streams"][$i] = $this->_data["model"]->getStream(user()->id, $i);
                }
            }
        } else {

            $this->_data['stream'] = $this->_data['model']->getStream();
            for ($i = 1; $i <= $this->_data["model_cams"]; $i++) {
                $this->_data["model_streams"][$i] = $this->_data["model"]->getStream(null, $i);
            }
        }

        /*        //include APPLICATION_PATH."/../public/flashservices/services/PeerToPeer.php";
                $_SESSION['stream'] = $this->_data['stream'];

                $channelName = "model_".$this->_data['model']->id;
                $_SESSION['channel'] = $channelName;

                //$rtmp = "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/".$channelName;
                $rtmp = config()->rtmp;
                $_SESSION['rtmp'] = $rtmp;
                $this->_data['rtmp'] = $rtmp;*/

        /*
        $channel = array(
            "channel" => $channelName,
            "password" => "PASSWORD",
            "stream" => $_SESSION['stream'],
            "rtmfp" => "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/"
        );

        $_SESSION['new_channel'] = $channel;
        */

        //getUsers + getChats
        $users = $this->chat->getUsers($this->_data['model']->id, $this->view);
        $users_list = $users['users'];
        //sort users
        usort($users_list, "cmp_webchat_users");
        $users['users'] = $users_list;

        if (isset($_COOKIE["reset_counter"]) && $_COOKIE["reset_counter"] == true) {

            //update timer user
            $this->load('webchat_users');
            $this->webchat_users->updateLogged($this->_data['model']->id, user()->id);
            unset($_COOKIE["reset_counter"]);
            setcookie("reset_counter", "false", time() - 3600);

        }

        $chats = $this->chat->getChats($this->_data['model']->id, $this->view);
        $loggedin = $this->webchat_users->getLogged($this->_data['model']->id, user()->id);

        if ($loggedin) {
            $this->_data['timer'] = time() - $loggedin->loggedin;
        } else {
            $loggedin = 0;
            $this->_data['timer'] = 0;
        }
        $this->_data['post_init'] = json_encode(array("getUsers" => $users, "getChats" => $chats));

        // user broad cast in private mode
        // if chat type private allow user to use webcam
        if (Auth::isUser() && $this->_data['model']->chat_type == 'private') {

            $this->load("user");
            $this->_data["user"] = $user = $this->user->getUserById(user()->id);
            unset($_SESSION['streams']["user"]);
            $_SESSION['streams']["user"][] = $user->getStream(user()->id);

            $rtmp = config()->rtmp;
            $_SESSION['rtmp'] = $rtmp;

            $this->_data['rtmp'] = $rtmp;
        }

        //get view helper
        $sm = Zend_Registry::get("service_manager");
        $this->em = Zend_Registry::get('service_manager')->get('Doctrine\ORM\EntityManager');

        $userRepo = $this->em->getRepository('Application\Entity\User');
        $this->_data['performer'] = $userRepo->findOneById($this->_data["model"]->id);
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function userprofileAction()
    {
        $this->load('user');

        $this->_data['user'] = $this->user->getUserById($this->request->id);

        $this->load('timezones');
        $this->_data['timezones'] = $this->timezones->fetchAll();

//        if (!$this->_data['user']->user_id) $this->_redirect("/404/");

        $this->_data['profile_action'] = $this->_request->profile_action;

        switch ($this->_request->profile_action) {

            case "profile":
                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-profile", "view")) $this->_redirect("/404");

                $this->_data['page_title'] = $this->_data['user']->username . ' - member profile';

                break;

            case "favorite":

                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-favorite", "view")) $this->_redirect("/404");

                $this->load('model');

                $nr = 10;
                $post = $this->params;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->model->getFavorite($this->_data['user']->id));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                $this->_data['page_title'] = $this->_data['user']->username . ' - favorite models';

                break;

            case "following":

                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-following", "view")) $this->_redirect("/404");

                $this->load('model');

                $nr = 10;
                $post = $this->params;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->model->getFollowingByUser($this->_data['user']->id));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                $this->_data['page_title'] = $this->_data['user']->username . ' - following models';

                break;

            case "friends":

                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-friends", "view")) $this->_redirect("/404");

                $this->_data['page_title'] = $this->_data['user']->username . ' - friends';

                break;

            case "edit":

                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-edit", "view")) $this->_redirect("/404");

                if (!Auth::isUser() || $this->_data['user']->id != $_SESSION['user']['id']) $this->_redirect("/404/");

                $this->load('countries');
                $this->_data['countries'] = $this->countries->fetchAllLocations('co');
                $this->_data['page_title'] = $this->_data['user']->username . ' - edit my account';

                $this->load("user_newsletter");

                if ($this->request->isPost()) {

                    $post = $this->_request->getPost();
                    if ($this->_request->save == 'Save') {

                        $subscription = ($post["newsletter"] == "on" ? 1 : 0);
//                        $this->user_newsletter->update(array("send" => $subscription), new Zend_Db_Expr("id_user=" . user()->id . " AND id_website=" . $_SESSION["website"]["id"]));

                        //@todo fix newsletter, as it does not work and throws an mysql exception!!!!!!!!!!!!!!!!!!!!!
                        unset($post["newsletter"]);

                        if (isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                            $photo_dir = APPLICATION_PATH . '/../../public/uploads/user/';
                            $this->load('upload');

                            $upload = $this->upload->uploadPhoto($photo_dir, "avatar");

                            if ($upload['status'] == 'success') {
                                $filename = $upload['file'];
                                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 260, 190);
                                $post['avatar'] = $filename;

                                //delete old avatar from server
                                if ($this->_data['user']->avatar) unlink($photo_dir . $this->_data['user']->avatar);
                            } else {
                                $photo_upload_failed = "<br>" . $upload['message'];
                            }
                        }

                        if(isset($post['privacy'])) {
                            $accepted = array('normal','less','more');
                            if(!in_array($post['privacy'],$accepted)) {

                                throw new \Exception('This value is not accepted for this field(chips_privacy');
                            }
                            $this->load('user');
                            $this->user->update(array('chips_privacy' => $post['privacy']), 'id = '.$this->_data['user']->id);

                        }

                        $post['birthday'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
                        unset($post['_birthday']);
                        unset($post['birthday_year']);
                        unset($post['birthday_month']);
                        unset($post['birthday_day']);
                        unset($post['save']);
                        unset($post['chips']);
                        unset($post['privacy']);
                        // p($post,1);

                        /* bad words filters */

                        $this->load("bad_words");
                        $badWords = $this->bad_words->getAllArray();
                        array_walk($post, 'badWords', $badWords);

                        /* end bad words filter */

                        $this->user->update($post, $this->user->getAdapter()->quoteInto("id=?", $this->_data['user']->id));
                        $this->_helper->FlashMessenger->addMessage(notice("Your profile has been successfully saved!" . $photo_upload_failed));
                        $this->_redirect($this->view->url(array("profile_action" => "edit", "id" => $this->_data['user']->id, "name" => $this->_data['user']->username), "user_profile"));
                    }
                    $this->_redirect($this->view->url(array("profile_action" => "edit", "id" => $this->_data['user']->id, "name" => $this->_data['user']->username), "user_profile"));

                }


                break;

            case "special-requests":
                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-specialrequests", "view")) $this->_redirect("/404");

                if (!Auth::isUser() || $this->_data['user']->id != $_SESSION['user']['id']) $this->_redirect("/404/");

                $this->_data['page_title'] = $this->_data['user']->username . ' - Special Requests';

                $this->load('special_requests');

                $nr = 20;
                $post = $this->params;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->special_requests->getRequests($_SESSION['user']['id'], ($this->_data["id"] ? $this->_data["id"] : null)));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "videos":

                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-videos", "view")) $this->_redirect("/404");

                if (!Auth::isUser() || $this->_data['user']->id != $_SESSION['user']['id']) $this->_redirect("/404/");

                $this->load('video');
                $this->_data['page_title'] = $this->_data['user']->username . ' - Bought Videos';

                $nr = 10;
                $post = $this->params;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->video->getUserPaidVideos($this->_data['user']->id, NULL, NULL));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;


                break;

            case "chips":
                $this->load('payments');
                $this->load('chips');
                if (!$this->acl->isAllowed("theme-website", "theme-userprofile-chips", "view")) $this->_redirect("/404");

                $this->_data['page_title'] = 'Chips';
                $this->_data['chips_history'] = $this->payments->userChipsHistory($_SESSION['user']['id']);
                $limit = $this->chips->getSenderChips($_SESSION['user']['id'])->toArray();

                if ($this->_request->isPost()) {
                    $post = $this->_request->getPost();

                    if ($post['send'] == 'Transfer Chips') {
                        //p(config()->chips_transfer_limit);
                        //p($post);

                        if (md5($post['password']) == $_SESSION['user']['password'] && $post['amount'] < $_SESSION['user']['chips'] && $limit['limit'] < config()->chips_transfer_limit) {

                            $id_user = $_SESSION['user']['id'];

                            // update sender chips
                          //  $this->chips->updateSenderChips($post['amount'], $_SESSION['user']['id']);

                            //update receiver chips
                          //  $this->chips->updateReceiverChips($post['amount'], $_SESSION['user']['id'], $post['receiver_type']);

                            $data = array('id_sender' => $_SESSION['user']['id'],
                                'id_receiver' => $post['id_receiver'],
                                'receiver_type' => $post['receiver_type'],
                                'data' => time(),
                                'amount' => $post['amount'],
                                'type' => 'transfer'
                            );

                            $this->chips->insert($data);

                            session_start();
                            $_SESSION['user']['chips'] = $this->chips->getChips($id_user);

                        }
                    }
                }

                break;
            default:
                $this->_redirect('/404/');
                break;
        }

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function messagesAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-messages", "view")) $this->_redirect("/404");

        $this->load('messages');
        $this->load('model');
        $this->load('user');
        $this->load('moderator');

        if (!Auth::isUser()) $this->_redirect("/404/");
        $this->_data['user'] = $this->user->getUserById($_SESSION['user']['id']);

        $this->_data['message_action'] = $this->_request->message_action;

        $post = $this->params;

        if ($this->_request->isPost() && $this->_request->message_action != "compose") {

            // if(isset($post["delete"]))
            //     $this->messages->deleteMessages($post["multiple_select"]);
            if (isset($post["archive"]))
                $this->messages->archiveMessages($post["multiple_select"]);
            if (isset($post["delete"]))
                $this->messages->deleteMessages($post["multiple_select"]);
            if (isset($post["read"]))
                $this->messages->updateMessages($post["multiple_select"], "1");
            if (isset($post["unread"]))
                $this->messages->updateMessages($post["multiple_select"], "0");
            /* refresh count */
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
        }

        switch ($this->_request->message_action) {

            case "inbox":

                $this->_data['page_title'] = 'Inbox';

                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();
                $this->_data["users"] = $users;
                print_r($this->messages->getUserInbox($_SESSION['user']['id']));
                die();

                $paginator = Zend_Paginator::factory($this->messages->getUserInbox($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "archive":

                $this->_data['page_title'] = 'Inbox';

                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();
                $this->_data["users"] = $users;

                $paginator = Zend_Paginator::factory($this->messages->getUserArchive($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "outbox":

                $this->_data['page_title'] = 'Sent Messages';

                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();
                $this->_data["users"] = $users;

                $paginator = Zend_Paginator::factory($this->messages->getUserOutbox($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "compose":

                $this->_data['page_title'] = 'compose message';

                if ($this->request->isPost()) {

                    $this->load('chips');

                    $post = $this->_request->getPost();
                    if ($post['send'] == 'Send Message' && $post['sendto'] && $post['subject'] && $post['message']) {
                        $this->load('model');


                        $the_model = $this->model->getModelByScreen_name($post['sendto']);
                        if (!$the_model->id) {
                            $this->_helper->FlashMessenger->addMessage(notice("There is no model with that name!"));
                            $this->_redirect($this->view->url(array("message_action" => "compose"), "messages"));
                        }

                        // this is only user to model !!!!
                        $post['id_sender'] = $_SESSION['user']['id'];
                        $post['sender_type'] = 'user';
                        $post['id_receiver'] = $the_model->id;
                        $post['receiver_type'] = 'model';
                        $post['send_date'] = time();

                        unset($post['sendto']);
                        unset($post['send']);


                        $user_chips = $this->chips->getChips($_SESSION['user']['id']);
                        if ($post['tip'] < 0 || $post['tip'] > $user_chips) {
                            $this->_helper->FlashMessenger->addMessage(notice("Tip can not exceed more than you have in your virtual wallet!"));
                            $this->_redirect($this->view->url(array("message_action" => "compose"), "messages"));
                        }

                        /* bad words filters */

                        $this->load("bad_words");
                        $badWords = $this->bad_words->getAllArray();
                        array_walk($post, 'badWords', $badWords);

                        /* end bad words filter */

                        if ($this->messages->insert($post)) {
                            //transfer tip chips to model
                            $this->chips->useChips($the_model->id, $post['tip'], $_SESSION['user']['id'], 'private_message');
                            $_SESSION['user']['chips'] -= $post['tip'];

                            $this->_helper->FlashMessenger->addMessage(notice("Private message sent."));
                            unset($_SESSION['form_special_request']);

                            $addNotification = array(
                                "id_from" => $_SESSION['user']['id'],
                                "type_from" => $_SESSION['group'],
                                "id_to" => $post['id_receiver'],
                                "type_to" => $post['receiver_type'],
                                "type" => "new_message",
                                "notification" => "New message from " . $_SESSION['user']['username'],
                                "ip" => $_SERVER["REMOTE_ADDR"],
                                "read" => "0",
                                "date" => time(),
                                "resource" => $this->messages->getAdapter()->lastInsertId()
                            );
                            $this->addNotification($addNotification, $post['id_receiver']);

                        } else {
                            $this->_helper->FlashMessenger->addMessage(notice("Something wrong happened. Private message was not sent!"));
                        }

                    }

                    $this->_redirect($this->view->url(array("message_action" => "compose"), "messages"));
                }

                break;

            default:
                $this->_redirect('/404/');
                break;
        }
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function notificationsAction()
    {

        $this->load('messages');
        $this->load('model');
        $this->load('user');
        $this->load('moderator');

        $this->_data['user'] = $this->user->getUserById($_SESSION['user']['id']);

        $post = $this->params;
        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        $this->load("user_notifications");
        $post = $this->_request->getPost();
        if ($post) {
            if ($post["mark_read"] || $post["mark_unread"]) {
                if ($post["mark_read"]) $read = 1;
                else $read = 0;
                $this->user_notifications->markNotifications($post["multiple_select"], $read);

                $this->_helper->FlashMessenger->addMessage(notice("Changes saved"));
                $this->_redirect($this->view->url(array(), "notifications"));
            } elseif ($post["mark_delete"]) {
                $this->user_notifications->deleteNotifications($post["multiple_select"]);
                $this->_helper->FlashMessenger->addMessage(notice("Notifications deleted"));
                $this->_redirect($this->view->url(array(), "notifications"));
            }
        }


        $notifications = $this->user_notifications->getAllType($_SESSION["group"], $_SESSION["user"]["id"], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
        foreach ($notifications as $n) {
            $last_notification = $n->id;
            break;
        }
        if ($last_notification > $_SESSION["user"]["last_notification"]) {
            //update session
            $_SESSION["user"]["last_notification"] = $last_notification;
            $this->load("model");
            $this->model->update(array("last_notification" => $last_notification), "id=" . $_SESSION["user"]["id"]);
        }

        $paginator = Zend_Paginator::factory($notifications);
        $paginator->setItemCountPerPage("25");
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

    }

    /**
     *
     */
    public function purchasechipsAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-purchasechips", "view")) $this->_redirect("/404");

        if (!Auth::isUser()) {
            $this->redirectToLogin('user', $this->getRequest()->getRequestUri());
        }
        $this->_redirect('/gateway/');
    }

    /**
     * @throws Zend_Exception
     */
    public function videoAction()
    {

        //if (!$this->acl->isAllowed("theme-website", "theme-video", "view")) $this->_redirect("/404");

        $this->load('video');
        $this->load('categories');
        $this->load('moderator');
        $this->load('model');
        $this->load('user');
        $this->view->commentCustom = Zend_Registry::get('service_manager')->get('viewhelpermanager')->get('commentCustom');

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if (isset($post["approve_video"]))
                $this->video->update(array("active" => 1), "id=" . (int)$this->params["id"]);
            elseif (isset($post["deny_video"]))
                $this->video->update(array("active" => 2), "id=" . (int)$this->params["id"]);
        }

        if (Auth::isModerator())
            $this->_data['video'] = $this->video->getVideo($this->_request->id, false);
        else
            $this->_data['video'] = $this->video->getVideo($this->_request->id);

        $this->_data['categories'] = $this->categories->getCategoriesByVideo($this->_request->id);


        $this->load('reviews');

        //users array for name
        $users["moderator"] = $this->moderator->getNames();
        $users["user"] = $this->user->getNames();
        $users["model"] = $this->model->getNames();
        $this->_data["users"] = $users;


            $this->_data["reviews"] = $this->reviews->getReviews($this->_request->id, "video", true);
        if (Auth::isLogged()) {
            $this->view->reviews_pending = $this->reviews->getPendingByUserId(user()->id, 'video', $this->_request->id);
        }

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();


            if ($post["add_review"]) {
                $rev = array(
                    "id_user" => $_SESSION["user"]["id"],
                    "user_type" => $_SESSION["group"],
                    "date" => time(),
                    "review" => ($post["new_review"]),
                    "resource_type" => "video",
                    "resource_id" => $this->_data['video']->id,
                );
                //p($this->_data['video'],1);
                $this->reviews->insert($rev);
                $last_id = $this->reviews->getAdapter()->lastInsertId();

                $this->_helper->FlashMessenger->addMessage(notice("Your review has been successfully saved! It will be moderated and posted!"));

                $addNotification = array(
                    "id_from" => $_SESSION["user"]["id"],
                    "type_from" => $_SESSION["group"],
                    "id_to" => $this->_data['video']->assigned_to ? $this->_data['video']->assigned_to : '0',
                    "type_to" => "moderator",
                    "type" => "reviews_video",
                    "notification" => ucfirst($_SESSION["group"]) . " " . $_SESSION["user"]["screen_name"] . " posted video review",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "linked_resource" => $this->_data["video"]->id,
                    "resource" => $last_id
                );
                // var_dump($addNotification);
                //   p($addNotification,1);
                $this->addNotification($addNotification, "moderator");
                $addNotification["id_to"] = $this->_data['model']->id;
                $addNotification["type_to"] = "model";
                $this->addNotification($addNotification, "model");

                $this->_redirect($this->view->url(array("id" => $this->_data["video"]->id, "name" => $this->_data["video"]->title), "video") . "#reviews");
            }
        }

        if ($this->_data['video']->type == 'vod') {

            $this->_data['isPaidVideo'] = $this->video->checkUserPaidVideo($this->_data['video']->id);

            if ($this->request->isPost() && !$this->_data['isPaidVideo']) {
                $this->load('chips');
                $post = $this->_request->getPost();
                if ($post['unlock']) {
                    if (!Auth::isUser()) {
                        $this->redirectToLogin('user', $this->getRequest()->getRequestUri());
                    }
                    $current_chips = $this->chips->getChips($_SESSION['user']['id']);
                    if ($current_chips < config()->vod_price) $this->_data['notice'] = notice("You do not have enough tokens to perform this operation!", false);

                    $this->video->addPaidVideo($_SESSION['user']['id'], $this->_data['video']->id, config()->vod_price);
                    $this->chips->useChips($this->_data['video']->id, config()->vod_price, $_SESSION['user']['id'], 'video');
                    $_SESSION['user']['chips'] = $this->chips->getChips($_SESSION['user']['id']);

                    $this->_redirect($_SERVER['REQUEST_URI']);
                }
            }
        } // end if type == 'vod'
        //update video views count
        if (!is_array($_SESSION['mysql']['videos']['views'])) $_SESSION['mysql']['videos']['views'] = array();
        if (!in_array($this->_data['video']->id, $_SESSION['mysql']['videos']['views'])) {
            $this->video->update(
                array(
                    "views" => new Zend_Db_Expr("views + 1")
                ),
                "id=" . (int)$this->params["id"]);


            $_SESSION['mysql']['videos']['views'][] = (int)$this->params["id"];
        }

        $this->_data['more_videos'] = $this->video->getVideos($this->_data['video']->user, null, null, 'id desc', 6);
        $this->_data['related_videos'] = $this->video->getRelatedVideos(null, null, null, 'id asc', 3);

    }

    /**
     * @throws Zend_Mail_Exception
     */
    public function contactAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-contact", "view")) $this->_redirect("/404");

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if (!isset($post['email']) || !isset($post['reason']) || !isset($post['message']) || !isset($post['username']) || !isset($post['captcha'])) {
                $this->_helper->FlashMessenger->addMessage(notice("Please fill all the fields.", false));
                $this->_redirect('/contact/');
            }


            unset($mail);
            $mail = new Zend_Mail();
            $mail->setFrom($post['email']);
            $mail->addTo(config()->contact_mail);
            $mail->setSubject(config()->site_name . ' - ' . $post['reason']);
            $mail->setBodyHtml($post['message'] . "<br><br>" . "Username: " . $post['username'] . "<br>Email: " . $post['email']);

            if ($mail->send()) {
                $this->_helper->FlashMessenger->addMessage(notice("Thank you for the e-mail. We'll get back to you as soon as possible!"));
                $this->_redirect('/contact/');
            } else {
                $this->_helper->FlashMessenger->addMessage(notice("Contact form not sent. Please try again.", false));
                $this->_redirect('/contact/');
            }

        }

    }

    /**
     * @throws Zend_Exception
     */
    public function verifyAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-verify", "view")) $this->_redirect("/404");

        if ($this->request) {
            $code = $this->_request->getParam("code");
            if ($code) {
                $this->load("user");
                $response = $this->user->verifyEmail($code);
                $this->_helper->FlashMessenger->addMessage(notice($response["message"]));

                $this->load("templates");
                $tmpl = $this->templates->getContent("member_welcome_message");

                if ($response["status"] == "success") {
                    $tmpl->content = str_replace("{name}", $response["name"], $tmpl->content);

                    $message["id_sender"] = 0;
                    $message["sender_type"] = "moderator";
                    $message["id_receiver"] = $response["user"];
                    $message["receiver_type"] = "user";
                    $message["subject"] = $tmpl->title ? $tmpl->title : "Welcome";
                    $message["message"] = $tmpl->content ? $tmpl->content : "Welcome to our site";
                    $message["inbox"] = 1;
                    $message["outbox"] = 0;
                    $message["send_date"] = time();
                    $message["read"] = 1;
                    $message["tip"] = 0;
                    $message["type"] = "inbox";

                    $this->load("messages");
                    $this->messages->insert($message);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $response["user"],
                        "type_to" => "user",
                        "type" => "new_message",
                        "notification" => $tmpl->title ? $tmpl->title : "Welcome",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "read" => "0",
                        "date" => time(),
                        "resource" => $this->messages->getAdapter()->lastInsertId()
                    );
                    $this->addNotification($addNotification, $response["user"]);
                }

                $this->redirectToLogin('user', $this->getRequest()->getRequestUri());

            } else {
                $this->_redirect('/');
            }
        } else {
            $this->_redirect('/');
        }
        exit;
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Mail_Exception
     */
    public function pwresetAction()
    {

        if (!$this->acl->isAllowed("theme-website", "theme-pwreset", "view")) $this->_redirect("/404");

        if ($_SESSION['user']['id']) $this->_redirect('/');
        $this->load('user');
        $this->_data['var'] = $this->request->var;

        if ($this->request->var && $this->request->var != 'done') {
            $this->_data['user'] = $this->user->fetchRow($this->user->select()->where("reset_code=?", $this->request->var));
        }

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if ($post['submit'] == 'Submit' && $post['email']) {

                $get_user = $this->user->fetchRow($this->user->select()->where("email=?", $post['email']));

                if (!$get_user->email) {
                    $this->_helper->FlashMessenger->addMessage(notice("We could not find an account that matches that email", false));
                    $this->_redirect('/pwreset/');
                }

                if ($get_user->state == 0) {
                    $this->_helper->FlashMessenger->addMessage(notice("You need to activate your account first.", false));
                    $this->_redirect('/pwreset/');
                }

                $reset_code = md5(md5(microtime() . $get_user->email . 'n8&^W') . '-' . substr(md5('p8*&g+' . rand(11, 99999) . 'cu7^S'), 2, 14));

                $link = "http://" . strtolower(config()->site_name) . "/pwreset/" . $reset_code;

                $this->load("templates");
                $tmpl = $this->templates->getContent("password_recovery");

                $message = $tmpl->content;
                $message = str_replace("{name}", $get_user->username, $message);
                $message = str_replace("{url}", $link, $message);
                $message = str_replace("{link}", '<a href="' . $link . '">click here</a>', $message);

                $mail = new Zend_Mail();
                $mail->setFrom('no-reply@' . strtolower(config()->site_name), config()->site_name);
                $mail->addTo($get_user->email, $get_user->username);
                $mail->setSubject($tmpl->title);
                $mail->setBodyHtml($message);

                if ($mail->send()) {
                    $this->user->update(array("reset_code" => $reset_code), "id=" . $get_user->id);
                    $this->_helper->FlashMessenger->addMessage(notice("An email has been sent to <i>" . $get_user->email . "</i> that will allow you to reset your password"));
                    $this->_redirect('/pwreset/done');
                } else {
                    $this->_helper->FlashMessenger->addMessage(notice("There was a problem, please try again", false));
                }
                if (!isset($notice_msq)) $notice_msq = '';
                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            if ($post['reset'] == 'Reset' && $post['password'] != '' && $post['password'] == $post['confirm_password']) {

                if (!$this->_data['user']->id) {
                    $this->_helper->FlashMessenger->addMessage(notice("Invalid verification code", false));
                    $this->_redirect('/pwreset/' . $this->request->var);
                }

                $this->user->update(array("password" => md5($post['password']), "reset_code" => ""), "id=" . $this->_data['user']->id);

                $this->_helper->FlashMessenger->addMessage(notice("Your password successfully changed. You can login now.", true));

                $this->redirectToLogin('user');

            }

            $this->_redirect('/pwreset/');
        }

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function modelprofileAction()
    {

        $this->load('model');

        $this->load('followers'); //
        $this->load('chips');
        $this->load("user_favorites");

        $this->load('info');
        $this->load('model_info');

        $id_model = $this->_data["id_model"];
        if ($id_model) {
            $this->_data['model'] = $this->model->getModelById($id_model);
        }

        if (!($this->_data['model']->id) || $this->_data['model']->status != 1) $this->_redirect('/404/');

        //get more stuff about the model
        //$this->_data['country'] = $this->countries->getLocationById($this->_data['model']->country);
        //$this->_data['info_fields'] = $this->model_info->getInfoByModel($id_model);
        //$this->_data['rates_fields'] = $this->model_rates->getRatesByModel($this->_data['model']->id);
        $this->_data['isFollowing'] = Auth::isLogged() ? $this->followers->isFollowing($this->_data['model']->id) : '';
        $this->_data['profile_type'] = $this->_request->profile_type;

        if (Auth::isLogged()) {
            //get model favorite status

            $is_favorite = $this->user_favorites->fetchRow($this->user_favorites->select()->from("user_favorites")->where("id=?", user()->id)->where("id_model=?", $this->_data['model']->id));
            if (count($is_favorite) == 1) $this->_data['favorite'] = true;
            else $this->_data['favorite'] = false;

            //$this->_data['nr_isfollowing'] = $nr_isfollowing = $this->followers->fetchRow($this->followers->select()->from("followers", "count(id_model) as count")->where("id_user=?", user()->id))->count;

        } else {
            $this->_data['nr_isfollowing'] = 0;
        }

        //$nr_favorite = $this->user_favorites->fetchRow($this->user_favorites->select()->from("user_favorites", "count(id_user) as count")->where("id_model=?", $this->_data['model']->id))->count;
        //$this->_data['nr_favorite'] = $nr_favorite;

        //$nr_follow = $this->followers->fetchRow($this->followers->select()->from("followers", "count(id_user) as count")->where("id_model=?", $this->_data['model']->id))->count;
        //$this->_data['nr_follow'] = $nr_follow;

        //$nr_friends = $this->user_favorites->fetchRow($this->user_favorites->select()->from("user_favorites", "count(id_user) as count")->where("id_model=?", $this->_data['model']->id))->count;

        $serviceLocator = Zend_Registry::get('service_manager'); //zf2 servicelocator
        $userRepo = $serviceLocator->get('doctrine.entity_manager.orm_default')->getRepository('Application\Entity\User');
        $this->_data['performer'] = $performer = $userRepo->findOneBy(array('id' => $this->_data['model']->id));
        $this->_data['nr_friends'] = 0;
        if ($performer) {
            $this->_data['nr_friends'] = $performer->getFriends()->count();
        }


        switch ($this->_request->profile_type) {

            case "profile":
                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-profile", "view")) $this->_redirect("/404");
                break;

            case "wall":
                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-wall", "view")) $this->_redirect("/404");
                break;

            case "pictures":
                $this->load('albums');

                if (Auth::isModel() && $this->_data['model']->id == user()->id)
                    $this->_data['albums'] = $this->albums->getAlbums($this->_data['model']->id, false, $active = false, $parent_id = 0);
                else
                    $this->_data['albums'] = $this->albums->getAlbums($this->_data['model']->id, true, $active = 1, $parent_id = 0);

                $this->load("photos");
                ///$this->_data['photos'] = $this->photos->recentPhotos($this->_data['model']->id);
                $page = isset($this->params["page"]) ? $this->params["page"] : 1;
                $paginator = Zend_Paginator::factory($this->photos->recentPhotos($this->_data['model']->id, 1));
                $paginator->setItemCountPerPage(12);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "videos":

                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-videos", "view")) $this->_redirect("/404");

                $post = $this->params;
                if (!isset($post['video_type'])) {
                    $this->_data['video_type'] = 'all';
                    $this->_data["nr"] = 6;
                } else {
                    $this->_data['video_type'] = $post['video_type'];
                    $this->_data["nr"] = 24;
                }

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $this->_data["page"] = 1;
                if (isset($post['page'])) {
                    $this->_data["page"] = $post['page'];
                    unset($post['page']);
                }

                break;

            case "offers":

                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-offers", "view")) $this->_redirect("/404");
                break;
            case "pledges":
                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-pledges", "view")) $this->_redirect("/404");

                $paginator = Zend_Paginator::factory($this->load("pledge")->selectAll($id_model, "start_date DESC", true, null));
                $paginator->setItemCountPerPage($this->_data["nr"]);
                $paginator->setCurrentPageNumber($this->_data["page"]);
                $this->view->paginator = $paginator;

                break;
            case "friends":
                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-wall", "view")) $this->_redirect("/404");

                $model = $serviceLocator->get('doctrine.entity_manager.orm_default')->getRepository('Application\Entity\User')
                    ->findOneBy(array('id' => $this->_data['model']->id));
                $criteria = new \Doctrine\Common\Collections\Criteria;
                $criteria::create()
                ->where($criteria::expr()->eq("status", 1));
                $this->_data['friends'] = array();
                if ($model) {
                    $this->_data['friends'] = $model->getFriends()->matching($criteria);
                }

                break;

            case "blog":
                break;

            case "schedule":
                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-schedule", "view")) $this->_redirect("/404");
                break;

            case "special-requests":
                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-specialrequests", "view")) $this->_redirect("/404");

                if (!Auth::isLogged()) {
                    $this->redirectToLogin('user', $this->getRequest()->getRequestUri());
                }

                $this->load('special_requests');
                $this->_data['special_items'] = $this->special_requests->getItems();

                $nr = 20;
                $post = $this->params;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->special_requests->getRequests($_SESSION['user']['id'], $id_model));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;


                if ($this->request->isPost()) {
                    $post = $this->_request->getPost();

                    if ($post['send'] == 'Send Request' && $post['item'] && $post['offer']) {
                        unset($_SESSION['form_special_request']);
                        unset($post['send']);

                        $_SESSION['form_special_request'] = $post;
                        $post['deposit'] = ceil($post['deposit']);

                        $post['duration'] = 0;
                        if ($post['duration_h']) $post['duration'] += 60 * 60 * $post['duration_h'];
                        if ($post['duration_m']) $post['duration'] += 60 * $post['duration_m'];
                        if ($post['duration_s']) $post['duration'] += $post['duration_s'];
                        unset($post['duration_h']);
                        unset($post['duration_s']);
                        unset($post['duration_m']);

                        if ($post['item'] == 'video' && ($post['duration'] < 60 || $post['duration'] > 3 * 3600)) {
                            $this->_helper->FlashMessenger->addMessage(notice("Please enter a valid duration for the video request!"));
                            $this->_redirect($_SERVER['REQUEST_URI']);
                        }
                        if (!$post['offer'] || $post['offer'] <= 0 || $post['deposit'] < 0) {
                            $this->_helper->FlashMessenger->addMessage(notice("Offer and deposit have to be numeric!"));
                            $this->_redirect($_SERVER['REQUEST_URI']);
                        }

                        $post['want_date'] = strtotime($post['want_date']);
                        if ($post['want_date'] < strtotime(date('m/d/Y'))) {
                            $this->_helper->FlashMessenger->addMessage(notice("Please enter a valid <i>Want it by</i> date!"));
                            $this->_redirect($_SERVER['REQUEST_URI']);
                        }

                        $user_chips = $this->chips->getChips($_SESSION['user']['id']);
                        if ($post['deposit'] < 0 || $post['deposit'] > $user_chips) {
                            $this->_helper->FlashMessenger->addMessage(notice("Deposit can not exceed more than you have in your virtual wallet!"));
                            $this->_redirect($_SERVER['REQUEST_URI']);
                        }

                        $post['id_user'] = $_SESSION['user']['id'];
                        $post['id_model'] = $id_model;
                        $post['added'] = time();
                        $post['last_update'] = time();


                        if ($this->special_requests->insert($post)) {
                            //transfer deposit chips to model
                            $this->chips->useChips($id_model, $post['deposit'], $_SESSION['user']['id'], 'special_request');
                            $_SESSION['user']['chips'] -= $post['deposit'];

                            $this->_helper->FlashMessenger->addMessage(notice("Special request sent."));
                            unset($_SESSION['form_special_request']);
                            $this->_redirect($this->view->url(array("profile_action" => "special-requests", "id" => user()->id, "name" => user()->username), "user_profile"));
                            exit;
                        } else {
                            $this->_helper->FlashMessenger->addMessage(notice("Something wrong happend. Special request was not sent!"));
                        }
                        $this->_redirect($_SERVER['REQUEST_URI']);
                    }
                }

                break;

            case "special-request":

                if (!$this->acl->isAllowed("theme-website", "theme-modelprofile-specialrequest", "view")) $this->_redirect("/404");
                if (!Auth::isUser()) {
                    $this->redirectToLogin('user', $this->getRequest()->getRequestUri());
                }
                $this->load('special_requests');
                $this->_data['special_request'] = $this->special_requests->getSpecialRequestById($this->_request->id_special_request);

                //check if owner
                if ($this->_data['special_request']->id_user != $_SESSION['user']['id']) $this->_redirect('/404');

                break;

            default:
                $this->_redirect('/404/');
                break;
        }
    }

    /**
     * @throws Zend_Exception
     */
    public function galleryAction()
    {

        $this->load('albums');
        $this->load('photos');
        $this->load('model');
        $this->load('user');
        $this->load('moderator');
        $this->load('reviews');
        $this->view->commentCustom = Zend_Registry::get('service_manager')->get('viewhelpermanager')->get('commentCustom');


        $this->_data['album'] = $this->albums->getAlbum($this->_request->id_gallery, false, false);
        if (Auth::isLogged())
        {
            $this->view->reviews_pending = $this->reviews->getPendingByUserId(user()->id, 'album', $this->_request->id_gallery);
        }

        if (!$this->_data['album']->id) $this->_redirect('/404/');

        if (
            $this->_data['album']->active != 1 &&
            !(
                (Auth::isModel() && user()->id == $this->_data["album"]->model_id) ||
                (Auth::isModerator() && (user()->id == $this->_data['album']->id_moderator || user()->id == 0))
            )
        ) {
            $this->_redirect('/404/');
        }

        $this->_data['model'] = $this->model->getModelById($this->_data['album']->model_id);

        //if (!$this->_data['model']->id || $this->_data['model']->active != 1) $this->_redirect('/404/');


        //if not viewable , redirect 404 except owner
        if (!$this->_data['album']->viewable && $_SESSION["group"] != "moderator" && $_SESSION["user"]["id"] != $this->_data['album']->model_id) {
            //$this->_redirect('/404/');
        }
        //if($_SESSION["group"] != "moderator" && $_SESSION["user"]["id"] != )

        if ((!($this->_data['album']->viewable >= mktime(0, 0, 0, date("n", time()), date("j", time()), date("Y", time()))) || !$this->_data['album']->viewable == 0) && $this->_data['album']->active != 1) {

            if (!Auth::isModerator() && !Auth::isModel())
                $this->_redirect('/404/');
        }

        if ($this->_data["album"]->parent_id > 0) {
            $parent = $this->albums->getAlbum($this->_data["album"]->parent_id, false);
            $this->_data["previous"] = $this->view->url(array("id_gallery" => $parent->id, "name" => $parent->name), "model-profile-gallery");
        }

        //$this->_data["model"] = $this->model->getModel($this->_data["album"]->id_model);
        //reviews
        $users = array();


        $this->_data["users"]["user"] = $this->user->getNames();
        $this->_data["users"]["model"] = $this->model->getNames();
        $this->_data["users"]["moderator"] = $this->moderator->getNames();

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            /* admin moderation */
            if ($this->_data["album"]->id && Auth::isModerator() && (user()->id == 0 || user()->id == $this->_data["album"]->id_moderator)) {
                //if(Auth::isModerator() && (user()->id == 0 || user()->id == $this->_data["pledge"]->id_moderator))

                if (isset($post["acceptButton"])) {
                    $this->albums->update(array("active" => 1), "id=" . (int)$this->_data["album"]->id);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["album"]->model_id,
                        "type_to" => "model",
                        "type" => "new_photo_album",
                        "notification" => 'Album "' . $this->_data["album"]->name . '" approved',
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["album"]->id
                    );
                    $this->addNotification($addNotification, $this->_data["album"]->model_id);

                    $this->photos->updateStatus(1, $this->_data["album"]->id, $this->_data["album"]->model_id, "photo");
                }
                if (isset($post["denyButton"])) {
                    $this->albums->update(array("active" => -1), "id=" . (int)$this->_data["album"]->id);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["album"]->model_id,
                        "type_to" => "model",
                        "type" => "new_photo_album",
                        "notification" => 'Album "' . $this->_data["album"]->name . '" denied',
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["album"]->id
                    );
                    $this->addNotification($addNotification, $this->_data["album"]->model_id);
                    $this->photos->updateStatus(-1, $this->_data["album"]->id, $this->_data["album"]->model_id, "photo");
                }

                $this->_redirect($this->view->url(array("id_gallery" => $this->_data['album']->id, "name" => $this->_data['album']->name), "model-profile-gallery"));
            }
            /* end moderation */

            if ($post["add_review"]) {
                $rev = array(
                    "id_user" => $_SESSION["user"]["id"],
                    "user_type" => $_SESSION["group"],
                    "date" => time(),
                    "review" => ($post["new_review"]),
                    "resource_type" => "gallery",
                    "resource_id" => $this->_data['album']->id,
                );

                $this->reviews->insert($rev);

                $last_id = $this->reviews->getAdapter()->lastInsertId();

                //notification && mesage
                $this->_helper->FlashMessenger->addMessage(notice("Your review has been successfully saved! It will be moderated and posted!"));

                $addNotification = array(
                    "id_from" => $_SESSION["user"]["id"],
                    "type_from" => $_SESSION["group"],
                    "id_to" => $this->_data['model']->assigned_to,
                    "type_to" => "moderator",
                    "type" => "reviews_gallery",
                    "notification" => ucfirst($_SESSION["group"]) . " " . $_SESSION["user"]["screen_name"] . " posted photo review",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "linked_resource" => $this->_data['album']->id,
                    "resource" => $last_id
                );

                $this->addNotification($addNotification, "moderator");

                $addNotification["id_to"] = $this->_data['model']->id;
                $addNotification["type_to"] = "model";
                $this->addNotification($addNotification, "model");

                $this->_redirect($this->view->url(array("id_gallery" => $this->_data['album']->id, "name" => $this->_data['album']->name), "model-profile-gallery") . "#reviews");
            }
        }

        $this->_data["reviews"] = $this->reviews->getReviews($this->_data["album"]->id, "album", true);

        if ($this->_data["album"]->id && (Auth::isModerator() && (user()->id == 0 || user()->id == $this->_data["album"]->id_moderator) || (Auth::isModel() && user()->id == $this->_data['album']->model_id)))
            $this->_data['photos'] = $this->photos->getPhotosByAlbumId($this->_data['album']->id, false);
        else
            $this->_data['photos'] = $this->photos->getPhotosByAlbumId($this->_data['album']->id);


        if ($this->_data['album']->password && Auth::isUser()) {
            $this->load('user_access');
            $this->_data['user_access'] = $this->user_access->getUserAccess($_SESSION['user']['id'], $this->_data['album']->id, 'album');
        }

        $this->_data['subalbums'] = $this->albums->getAlbums($this->_data['model']->id, true, $active = 1, $parent_id = $this->_data['album']->id);

        //DELETE photo album
        if ($this->_request->action_type == 'delete') {

            if (!Auth::isModel() || $_SESSION['user']['id'] != $this->_data['album']->model_id || !$this->acl->isAllowed($_SESSION['group'], "model-manage-photos", "edit")) $this->_redirect('/404/');

            if (count($this->_data['photos'])) {
                foreach ($this->_data['photos'] as $photo) {
                    $this->photos->deletePhoto($photo->filename, 'photo');
                }
            }

            $this->photos->delete(db()->quoteInto("reference_id=?", $this->_data['album']->id) . ' and ' . db()->quoteInto("entity=?", 'photos'));
            $this->albums->delete(db()->quoteInto("id=?", $this->_data['album']->id));

            $this->load("notifications");
            $this->notifications->addNotification(user()->id, $_SESSION['group'], $this->_data['album']->model_id, "model", "delete_photo_album", "photo album deleted.", 1, getUserIp());

            $this->_redirect($this->view->url(array("profile_type" => "pictures", "id_model" => $this->_data['model']->id, "name" => $this->_data['model']->screen_name), "model-profile"));
            exit;
        }

        //Password request, if need it
        if (!$_SESSION['user_access']['album']) $_SESSION['user_access']['album'] = array();
        if ($this->_request->action_type == 'pass_req') {

            if ($this->request->isPost()) {
                $post = $this->_request->getPost();

                if ($post['gallery_password'] != $this->_data['album']->password) {
                    $this->_helper->FlashMessenger->addMessage(notice('Invalid password!'));
                    $this->_redirect($this->view->url(array("id_gallery" => $this->_data['album']->id, "name" => $this->_data['album']->name), "model-profile-gallery"));
                }

                //good password
                if (Auth::isUser()) {
                    db()->query("insert into user_access set id_user = '" . $_SESSION['user']['id'] . "',
                                                             id_item = '" . $this->_data['album']->id . "',
                                                             item_type = 'album',
                                                             added='" . time() . "' ");
                } else {
                    //not logged in user, save access to $_SESSION
                    $_SESSION['user_access']['album'][] = $this->_data['album']->id;
                }

                $this->_redirect($this->view->url(array("id_gallery" => $this->_data['album']->id, "name" => $this->_data['album']->name), "model-profile-gallery"));
            }

            exit;
        }


        $this->load('model_info');
        $this->load('countries');
        $this->load('model_rates');
        $this->load('followers');
        $this->load('chips');


        //get more stuff about the model
        $this->_data['country'] = $this->countries->find($this->_data['model']->country)->current();
        $this->_data['info_fields'] = $this->model_info->getInfoByModel($this->_data['model']->id);
        $this->_data['rates_fields'] = $this->model_rates->getRatesByModel($this->_data['model']->id);
        $this->_data['isFollowing'] = Auth::isUser() ? $this->followers->isFollowing($this->_data['model']->id) : '';
        $this->_data['profile_type'] = 'pictures';

        if (Auth::isUser()) {
            //get model favorite status
            $this->load("user_favorites");
            $is_favorite = $this->user_favorites->fetchRow($this->user_favorites->select()->from("user_favorites")->where("id_user=?", user()->id)->where("id_model=?", $this->_data['model']->id));
            if (count($is_favorite) == 1) $this->_data['favorite'] = true;
            else $this->_data['favorite'] = false;
        }

    }

    /**
     * @throws Zend_Exception
     */
    public function imageAction()
    {

        $this->load('albums');
        $this->load('photos');
        $this->load('model');
        $this->load('model_rates');
        $this->load('reviews');
        $this->load("user");
        $this->load("moderator");
        $this->load('followers');

        $this->_data["image"] = $this->photos->getPhotoById($this->_request->id_image);
        $this->view->commentCustom = Zend_Registry::get('service_manager')->get('viewhelpermanager')->get('commentCustom');


            $this->_data["reviews"] = $this->reviews->getReviews($this->_request->id_image, "image", true);

        if (Auth::isLogged()) {

            $this->view->reviews_pending = $this->reviews->getPendingByUserId(user()->id, 'image', $this->_request->id_image);
        }
        //$this->_data["model"] = $this->model->getModel($this->_data["image"]->id_model);

        $this->_data['rates_fields'] = $this->model_rates->getRatesByModel($this->_data['image']->id_model);

        if ($this->_data["image"]->album_name)
            $this->_data["previous"] = $this->view->url(array("id_gallery" => $this->_data["image"]->reference_id, "name" => $this->_data["image"]->album_name), "model-profile-gallery");

        //reviews
        $users = array();

        $this->_data["users"]["user"] = $this->user->getNames();
        $this->_data["users"]["model"] = $this->model->getNames();
        $this->_data["users"]["moderator"] = $this->moderator->getNames();

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            /* admin moderation */
            if ($this->_data["image"]->id && Auth::isModerator() && (user()->id == 0 || user()->id == $this->_data["image"]->id_moderator)) {
                //if(Auth::isModerator() && (user()->id == 0 || user()->id == $this->_data["pledge"]->id_moderator))

                if (isset($post["acceptButton"])) {
                    $this->photos->update(array("active" => 1), "id=" . (int)$this->_data["image"]->id);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["image"]->id_model,
                        "type_to" => "model",
                        "type" => "image",
                        "notification" => 'Image approved',
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["image"]->id
                    );
                    $this->addNotification($addNotification, $this->_data["image"]->id_model);

                }
                if (isset($post["denyButton"])) {
                    $this->photos->update(array("active" => -1), "id=" . (int)$this->_data["image"]->id);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["image"]->id_model,
                        "type_to" => "model",
                        "type" => "image",
                        "notification" => 'Image denied',
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["image"]->id
                    );
                    $this->addNotification($addNotification, $this->_data["image"]->id_model);

                }

                $this->_redirect($this->view->url(array("id_image" => $this->_request->id_image), "model-image-gallery"));
            }
            /* end moderation */


            if ($post["add_review"]) {
                $rev = array(
                    "user_id" => $_SESSION["user"]["id"],
                    "user_type" => $_SESSION["group"],
                    "date" => time(),
                    "review" => ($post["new_review"]),
                    "resource_type" => "image",
                    "resource_id" => $this->_request->id_image,
                );

                $this->reviews->insert($rev);
                $last_id = $this->reviews->getAdapter()->lastInsertId();

                $this->_helper->FlashMessenger->addMessage(notice("Your review has been successfully saved! It will be moderated and posted!"));

                $addNotification = array(
                    "id_from" => $_SESSION["user"]["id"],
                    "type_from" => $_SESSION["group"],
                    "id_to" => $this->_data['model']->assigned_to,
                    "type_to" => "moderator",
                    "type" => "reviews_photo",
                    "notification" => ucfirst($_SESSION["group"]) . " " . $_SESSION["user"]["screen_name"] . " posted photo review",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "linked_resource" => $this->_data["image"]->id,
                    "resource" => $last_id
                );

                $this->addNotification($addNotification, "moderator");
                $addNotification["id_to"] = $this->_data['model']->id;
                $addNotification["type_to"] = "model";
                $this->addNotification($addNotification, "model");

                $this->_redirect($this->view->url(array("id_image" => $this->_request->id_image), "model-image-gallery") . "#reviews");
            }
        }


        $this->_data['isFollowing'] = Auth::isUser() ? $this->followers->isFollowing($this->_data['image']->id_model) : '';

        if ((!($this->_data['image']->viewable >= mktime(0, 0, 0, date("n", time()), date("j", time()), date("Y", time()))) || !$this->_data['image']->viewable == 0) && $this->_data['image']->status != 1) {

        }
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function premieresAction()
    {
        $this->load('shows');
        $this->load('photos');
        $this->load('video');
        $this->load('model');

        $nr = 6;
        $post = $this->params;

        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        if (isset($post['date'])) {
            $date = $post['date'];
            if ($post['id']) { // past shows for model
                $time = mktime(date('H', time()), date('i', time()), date('s', time()), substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
            } else { //pending shows for date
                $time = mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
                $to_time = mktime(23, 59, 59, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));

            }

        } else { //pending shows from now on
            $date = date('Y-m-d', time());
            $time = time();

        }

        if (!$post['id']) { //pending shows
            if (isset($post["s"])) {
                if (!$post['date']) $paginator = Zend_Paginator::factory($this->shows->getShows($time, 'premiere', 1, $post["s"]));
                else $paginator = Zend_Paginator::factory($this->shows->getShowsDate($time, $to_time, 'premiere', 1, $post["s"]));
            } else {
                if (!$post['date']) $paginator = Zend_Paginator::factory($this->shows->getShows($time, 'premiere', 1));
                else $paginator = Zend_Paginator::factory($this->shows->getShowsDate($time, $to_time, 'premiere', 1));
            }
        } else { //past shows for model
            if (isset($post["s"]))
                $paginator = Zend_Paginator::factory($this->shows->getPastShows($post['id'], $time, 'premiere', 1, $post["s"]));
            else
                $paginator = Zend_Paginator::factory($this->shows->getPastShows($post['id'], $time, 'premiere', 1));
        }

        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $photos = array();
        $videos = array();
        $models = array();
        foreach ($paginator as $show) {
            $photos[$show->reference_id] = $this->photos->getPhotosByModelId($show->id_model, $show->reference_id, "show");
            $videos[$show->reference_id] = $this->video->getVideos($show->id_model, "premieres", null, null, 1, $show->reference_id);
            $models[$show->reference_id] = $this->model->getModelById($show->id_model);
        }
        $this->_data['photos'] = $photos;
        $this->_data['videos'] = $videos;
        $this->_data['models'] = $models;

    }

    /**
     *
     */
    public function storeAction()
    {

    }

    /**
     *
     */
    public function auctionAction()
    {

    }

    /**
     *
     */
    public function playAction()
    {

    }

    /**
     *
     */
    public function lobbyAction()
    {

    }

    /**
     *
     */
    public function mostpopularroomAction()
    {
    }

    /**
     *
     */
    public function groupshowsAction()
    {
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function clipsAction()
    {

        $this->load('video');
        $this->_data['feat_videos'] = $this->video->getVideos(null, null, null, 'id desc', 4, null, "premieres");

        $nr = 12;
        $post = $this->params;

        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }


        if (isset($post['s'])) { //for search
            $videos = $this->video->getVideos(null, null, null, null, null, null, "premieres", $post["s"]);
        } else {
            $videos = $this->video->getVideos(null, null, null, null, null, null, "premieres", null);
        }
        $paginator = Zend_Paginator::factory($videos);
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

    }

    /**
     *
     */
    public function page404Action()
    {

    }

    /**
     * @throws Zend_Exception
     */
    public function pageAction()
    {

        $this->load('static_pages');

        if ($this->_request->page) {
            $this->_data['page_content'] = $this->static_pages->getContent($this->_request->page);
            if($this->_data['page_content']->link){
                $this->_redirect($this->_data['page_content']->link);
                exit;
            }
        } else {
            $this->_redirect("/404/"); //no valid page name
        }

        if (!$this->_data['page_content']) $this->_redirect("/404/"); //page url not valid !

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function wallAction()
    {

        $this->load("transfer_wall");

        $paginator = Zend_Paginator::factory($this->transfer_wall->getUserTransfers('user'));
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $this->view->paginator = $paginator;

        $paginator_model = Zend_Paginator::factory($this->transfer_wall->getUserTransfers('user'));
        $paginator_model->setItemCountPerPage(10);
        $paginator_model->setCurrentPageNumber($this->_getParam('page'));
        $this->view->paginator_model = $paginator_model;
    }
}
