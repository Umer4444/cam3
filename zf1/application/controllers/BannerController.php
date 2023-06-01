<?php

class BannerController extends App_Controller_Action
{

    private $layout_control = null;
    private $route_name = null;

    public function init()
    {
        $this->_data["route_name"] = $this->route_name = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        if (endsWith($this->route_name, "-frontend")) {
            $this->initIndex();
            $this->layout_control = "frontend";
        } elseif (endsWith($this->route_name, "-performer-backend")) {
            $this->initModel();
            $this->layout_control = "performer";
        } elseif (endsWith($this->route_name, "-moderator-backend")) {
            $this->initAdmin();
            $this->layout_control = "moderator";
        }
    }

    private function initModel()
    {

        parent::init();
        $action = $this->_request->action;


        $this->load("user_notifications");
        //$this->_data["notification_count"] = $this->user_notifications->getUnreadCount("model", $_SESSION["user"]["id"]);
        $this->_data["notification_count"] = $this->user_notifications->getNewNotificationCount("model", $_SESSION["user"]["id"], $_SESSION["user"]["last_notification"], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
        unset($this->user_notifications);

        //load development pages menu
        $this->load('static_pages');

        $parentPagesTop = $this->static_pages->getPages('backend');
        $this->_data['pages'] = array();

        foreach ($parentPagesTop as $pageTop) {
            $childrenTop = array();
            $parentPagesLvl1 = $this->static_pages->getPages('backend', $pageTop->page);
            foreach ($parentPagesLvl1 as $pageLvl1) {
                $childrenTop[] = array("page" => $pageLvl1->page, "title" => $pageLvl1->title);
            }
            $this->_data['pages'][] = array("page" => $pageTop->page, "title" => $pageTop->title, 'children' => $childrenTop);
        }

        if (Auth::isModel()) {
            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
            unset($this->messages);
        }


    }

    private function initIndex()
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


        if (Auth::isUser()) {

            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
            unset($this->messages);
        }
    }

    private function initAdmin()
    {


        parent::init();


        $this->load("user_notifications");
        $this->_data["notification_count"] = $this->user_notifications->getNewNotificationCount("moderator", $_SESSION["user"]["id"], $_SESSION["user"]["last_notification"], false);
        unset($this->user_notifications);

        if (Auth::isModerator()) {
            $this->load("moderator");
            $this->_data["moderator"] = $this->moderator->fetchRow($this->moderator->select()->where("id=?", user()->id));
            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
            unset($this->messages);
        }

    }

    public function listBannersAction()
    {
        if (Auth::isModel()) {
            $id_model = user()->id;
        } else if (isset($this->params["id_model"])) {
            $id_model = $this->params["id_model"];
        } else {
            $id_model = null;
        }

        $this->load("banners");
        $page = $this->params["page"];
        $nr = 1000000; //@TODO verify where is this, and CHANGE IT!!!!!!!!!!!!!!!!!!
        $paginator = Zend_Paginator::factory($this->banners->getBannersModel($id_model));
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("banner-list-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
    }

    public function moderateBannersAction()
    {
        /* moderation */
        //   p($this->params,1);

        {
            $post = $this->_request->getPost();

            if ($post) {


                $this->load("banners");

                if (isset($post["acceptButton"])) {
                    $this->banners->multipleAction($post["multiple_select"], "accept");


                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->params["id_model"],
                        "type_to" => "model",
                        "type" => "approve",
                        "notification" => "Banners approved",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => ""
                    );
                    $this->addNotification($addNotification, $this->params["id_model"]);

                }
                if (isset($post["denyButton"])) {
                    $this->banners->multipleAction($post["multiple_select"], "deny");

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->params["id_model"],
                        "type_to" => "model",
                        "type" => "deny",
                        "notification" => "Banners denied",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => ""
                    );
                    $this->addNotification($addNotification, $this->params["id_model"]);
                }
                if (isset($post["deleteButton"])) {
                    $this->banners->multipleAction($post["multiple_select"], "delete");
                }

                //$this->_redirect($this->view->url(array("id_pledge"=>$this->_data["pledge"]->id, "title" => ro_slug($this->_data["pledge"]->title)),"pledge"));

                $this->_redirect($this->view->url(array("name" => $this->params["name"], "id_model" => $this->params["id_model"]), "banner-list-moderator-backend"));
            }
        }
    }

    public function addBannersAction()
    {

        $this->load("banners");

        if (isset($this->params["id_banner"])) {
            $edit = true;
            $this->_data["banner"] = $this->banners->getById($this->params["id_banner"]);

            $id_banner = $this->_data["banner"]->id;
        }

        $post = $this->_request->getPost();

        if ($post) {

            $b_size = '';
            $b_zones = array();
            if (isset($post["banner_zone"])) {
                foreach ($post["banner_zone"] as $bz) {
                    $bzArr = explode('^', $bz);
                    $b_size = $bzArr[1];
                    $b_zones[] = $bzArr[0];
                }
            }


            if (!$edit) {
                $banner["user_type"] = $_SESSION["group"];
                $banner["id_user"] = user()->id;
            }
            $banner["banner_size"] = $b_size;
            $banner["banner_zone"] = json_encode($b_zones);

            $banner["start_date"] = strtotime($post["start_date"]);
            $banner["end_date"] = strtotime($post["end_date"]);
            $banner["content"] = $post["content"];
            $banner["id_owner"] = user()->id;

            if (Auth::isModerator())
                $banner["status"] = 1;
            else
                $banner["status"] = 2;
            if ($edit)
                $this->banners->update($banner, db()->quoteInto('id=' . (int)$id_banner));
            else
                $id_banner = $this->banners->insert($banner);
            if (Auth::isModel()) {
                $addNotification = array(
                    "id_from" => user()->id,
                    "type_from" => "model",
                    "id_to" => 0,
                    "type_to" => "moderator",
                    "type" => "banner",
                    "notification" => "Model added new banner",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "resource" => user()->id
                );

                $this->addNotification($addNotification, "admin");
            }
            $this->_helper->FlashMessenger->addMessage(notice("Banner saved"));

            if (Auth::isModerator())
                $this->_redirect($this->view->url(array("id_model" => $this->params["id_model"], "name" => $this->params["name"]), "banner-list-moderator-backend"));
            elseif (Auth::isModel())
                $this->_redirect($this->view->url(array(""), "banner-list-performer-backend"));

        }

        $this->_data['banner_zone'] = json_decode(config()->banner_zone);

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("banner-add-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
    }

    public function deleteBannersAction()
    {
        $this->load("banners");

         {
            if (Auth::isModerator())
                $id_model = $this->params["id_model"];
            else if (Auth::isModel())
                $id_model = user()->id;

            if ($this->params["id_banner"]) {
                $this->banners->multipleAction($this->params["id_banner"], "delete", $id_model);
                $this->_helper->FlashMessenger->addMessage(notice("Banner deleted"));
            }
            if (Auth::isModel())
                $this->_redirect($this->view->url(array(), "banner-list-performer-backend"));
            else if (Auth::isModerator())
                $this->_redirect($this->view->url(array("id_model" => $this->params["id_model"], "name" => $this->params["name"]), "banner-list-moderator-backend"));

        }
    }
}
