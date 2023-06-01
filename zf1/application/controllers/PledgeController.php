<?php

use PerfectWeb\Core\Utils\Status;

class PledgeController extends App_Controller_Action
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

        //if is model website, redirect to 404
        if (isset($_SESSION["website"]["url"]) && !empty($_SESSION["website"]["url"]))
            $this->_redirect("/404");

        parent::init();
        $action = $this->_request->action;

//        if (Auth::isModel() && ((($action != 'login') && ($action != 'logout') && ($action != 'index') && ($action != 'accountSettings') && ($action != 'profileSettings') && ($action != 'pwreset') && ($action != 'verify') && user()->active == 0) || user()->active == -1)) $this->_redirect("/model/");
//        die();
        if (!Auth::isModel()
            && (
                ($action != 'login'
                    && ($action != 'logout')
                    && ($action != 'index')
                    // && ($action != 'accountSettings')
                    // && ($action != 'profileSettings')
                    && ($action != 'pwreset')
                    && ($action != 'verify')
                    && user()->active == 0)
                || user()->active == -1)
        ) {
            $this->_redirect("/model/");
        }
        if (!Auth::isModel() && ($action != 'login') && ($action != 'pwreset') && ($action != 'signup') && ($action != 'upload') && ($action != 'verify')) {
            $this->redirectToLogin('model', $this->getRequest()->getRequestUri());
        }

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

        //if is model website, redirect to 404
        if (isset($_SESSION["website"]["url"]) && !empty($_SESSION["website"]["url"]))
            $this->_redirect("/404");

        parent::init();
        $action = $this->_request->action;
        if (!isset($_SESSION["role_switch"]) && !Auth::isModerator() && $action != 'login' && $action != 'pwreset') {
            $this->redirectToLogin('admin', $this->getRequest()->getRequestUri());
        }


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

    public function listpledgesAction()
    {

        if (Auth::isModel()) {
            $id_model = user()->id;
        } else if (isset($this->params["id_model"])) {
            $id_model = $this->params["id_model"];
        } else {
            $id_model = null;
        }

        $post = $this->params;

        $page = 1;

        if (isset($post["page"])) $page = $post["page"];

        $this->_data["filter"] = null;

        $page = $this->params["page"];

        $this->_data["return_to"] = "pledges";

        $this->load("categories");
        $this->_data["categories"] = $this->categories->getCategoriesArray("pledge");

        $this->_data["sorting_options"] = array(
            "popular" => "Popular now",
            "most_funded" => "Most funded",
            "new" => "New this week",
            "ending_soon" => "Ending soon"
        );


        // sort
        if (isset($post["sort"]) && !empty($post["sort"])) {
            if (array_key_exists(trim($post["sort"], ','), $this->_data["sorting_options"]))
                $this->_data["sort"] = trim($post["sort"], ',');
        }

        $this->_data["filter"] = array();

        //category
        $this->_data["filter"]["categories"] = null;
        $this->_data["filter"]["categories_id"] = array();
        if (isset($post["categories"])) {
            $arr_cat = explode(",", trim($post["categories"], ","));

            foreach ($arr_cat as $ctg) {
                $ctg = str_replace(array("_", "-"), array(" ", "/"), $ctg);
                if (array_search($ctg, $this->_data['categories'])) {
                    $this->_data["filter"]["categories_id"][] = array_search($ctg, $this->_data['categories']);
                    $this->_data["filter"]["categories"] .= str_replace(array(" ", "/"), array("_", "-"), $ctg) . ",";
                }
            }
        }

        //$this->_data["filter"]["categories"]    = array_filter($this->_data["filter"]["categories"]);
        $this->_data["filter"]["categories_id"] = array_filter($this->_data["filter"]["categories_id"]);


        /* filter orientation */
        $this->_data["filter"]["orientation"] = "";
        $this->_data["filter"]["orientation_id"] = array();
        if (isset($post["orientation"])) {
            $this->_data["filter"]["orientation"] = str_replace("_", " ", $post["orientation"]);
            $this->_data["filter"]["orientation_id"] = explode(",", $post["orientation"]);
        }
        $this->_data["filter"]["orientation_id"] = array_filter($this->_data["filter"]["orientation_id"]);


        $this->load("pledge");
        $pledges = array();
        switch ($this->layout_control) {
            case "frontend":
                $pledges = $this->pledge->selectAll($id_model, "start_date DESC", true, $this->_data["filter"]);
                break;
            case "moderator":
            case 'performer':
                $pledges = $this->pledge->selectAll($id_model, "p.id DESC", false, $this->_data["filter"]);
                break;
        }

        //p($this->_data["filter"],1);
        $paginator = Zend_Paginator::factory($pledges);
        $paginator->setItemCountPerPage(25);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("pledge-list-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
    }

    public function viewpledgeAction()
    {

        if (!isset($this->params["id_pledge"])) {
            $this->_redirect("/404");
        }

        $this->load("pledge");
        $this->load("pledge_perk");
        $this->load("albums");


        $this->load("pledge_funder");
        $this->load("photos");

        $this->_data["pledge"] = $this->pledge->getById(($this->params["id_pledge"]));

        if ($this->_data["pledge"]->status != 1) {
            if (!Auth::isLogged()
                || (Auth::isModerator() && (user()->id != 0 && user()->id != $this->_data["pledge"]->id_moderator))
                || (Auth::isModel() && user()->id != $this->_data["pledge"]->id_model)
            ) $this->_redirect("/404");
        }

        if (Auth::isModerator() && user()->id == 0 || user()->id == $this->_data["pledge"]->id_moderator)
            $status = 1;
        else
            $status = false;

        $this->_data["perks_model"] = $this->pledge_perk->getByIdPledge($this->_data["pledge"]->id, $status, "model");
        $this->_data["perks_moderator"] = $this->pledge_perk->getByIdPledge($this->_data["pledge"]->id, $status, "moderator");

        $this->load("pledge_update");
        $this->_data["updates"] = $this->pledge_update->getByIdPledge($this->params["id_pledge"]);


        $this->_data["albums"] = $this->albums->getAlbums($this->_data["pledge"]->id_model, $active = 1, $parent_id = null, $limit = 5, $start = 0, $type = 'all', $id_resource = null);
        if ($this->_data["albums"]) {
            foreach ($this->_data["albums"] as $album) {
                $this->_data["first_album"] = $this->albums->getCarouselPhotos($album->id);

                break;
            }
        }

        // if admin, moderator or owner load all photos else load only active
        if ($this->_data["pledge"]->id && Auth::isModerator() && (user()->id == 0 || user()->id == $this->_data["pledge"]->id_moderator))
            $this->_data["photos"] = $this->photos->getPhotosByAlbumId($this->_data["pledge"]->id, false);
        else
            $this->_data["photos"] = $this->photos->getPhotosByAlbumId($this->_data["pledge"]->id, true);

        switch ($this->layout_control) {
            case "frontend":

                $this->_data["funders"] = $this->pledge_funder->getFunders($this->params["id_pledge"]);
                $this->_data["largest_user"] = $this->pledge_funder->getLargestContributor($this->params["id_pledge"]);
                $this->_data["last_user"] = $this->pledge_funder->getLastContributor($this->params["id_pledge"]);
                $this->_data["end_date"] = ($this->_data["pledge"]->duration_type == "days" ? ($this->_data["pledge"]->start_date + ($this->_data["pledge"]->duration_days * 3600 * 24)) : $this->_data["pledge"]->duration);
                $this->_data["pledge_status"] = false;

                if ($this->_data["pledge"]->status == 1) $this->_data["pledge_status"] = true;
                if ($this->_data["pledge"]->funding_type == "fixed" && $this->_data["pledge"]->contributed_amount >= $this->_data["pledge"]->goal_amount) $this->_data["pledge_status"] = false;

                if (time() < time()+10000) $this->_data["pledge_status"] = false; //@TODO ADD END DATE

                $request = $this->_request;
                $post = $request->getPost();

                $this->load("pledge_perk");


                break;
            case "moderator":
            case 'performer':
                $pledges = $this->pledge->selectAll('61', "start_date DESC", false); //@TODO ADD MODEL ID!!!!!
                break;
        }

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("pledge-view-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
    }

    public function addpledgeAction()
    {

        if (Auth::isModel()) {
            $id_model = user()->id;
        } elseif ($this->params["id_model"]) {
             $id_model = $this->params["id_model"];
        } else {
            $this->redirectToLogin();
        }

        $this->load("pledge");

        if ($this->params["id_pledge"]) {
            $edit = true;
            $id_pledge = (int)$this->params["id_pledge"];
            $this->_data["pledge"] = $this->pledge->getById($id_pledge);
        } else {
            $edit = false;
        }

        $this->load("categories");
        $this->_data["categories"] = $this->categories->getCategoriesArray("pledge");

        $this->load("albums");
        $this->load("upload");

        $albums = $this->albums->getAlbums($id_model, $active = 0, $parent_id = null, $limit = 100, $start = 0, $type = 'pledge', $id_resource = "non");
        $this->_data["albums"] = array(null => '--');
        foreach ($albums as $album)
            $this->_data["albums"][$album->id] = $album->name;


        $this->load("categories");
        $this->_data["categories"] = $this->categories->getCategoriesArray("pledge");

        $photo_dir = 'public/uploads/photos/';

        $this->load("video");
        $videos = $this->video->getVideos($id_model, $type = "pledge", $private = null, $order = "uploaded_on DESC",
                                          $nr = null, $id_show = null, $not_type = null, $search = null, $active = false);

        if ($videos) {
            $this->_data["videos"][] = "--";
            foreach ($videos as $video)
                $this->_data["videos"][$video->id] = $video->title;
        } else {
            $this->_data["videos"][] = "--";
        }
        $request = $this->_request;
        $post = $request->getPost();

        if ($post) {

            if (empty($post["title"])) {
                $this->_helper->flashMessenger->addMessage(notice("Empty pledge title"));
                $this->_redirect($this->view->url(array(), "pledge-add-performer-backend"));
            }


            $rev_share = 100 - $_SESSION["user"]["rep_share"];

            $amount_chips = (int)$post["goal_amount"] + ((int)$post["goal_amount"] * ($rev_share) / 100);

            $pledge["id_model"] = $id_model;

            $pledge["title"] = $post["title"];
            $pledge["type"] = $post["pledge_type"];
            $pledge["goal_amount"] = round($amount_chips, 0, PHP_ROUND_HALF_EVEN);
            $pledge["start_date"] = empty($post["start_date"]) ? 0 : strtotime($post["start_date"]);
            $pledge["end_date"] = $post["duration_type"] == "until" ? strtotime($post["duration"]) : (strtotime($post["start_date"]) + ((int)$post["duration"] * 24 * 3600));
            $pledge["duration_days"] = $post["duration_type"] == "days" ? ((int)$post["duration"]) : 0;
            $pledge["content"] = $post["content"];
            $pledge["goal_rep_share"] = $rev_share;
            // @TODO this needs a new way to work, as we have oneToMany relation to video Entity
            //$pledge["id_video"] = (int)$post["video"];

            $pledge["duration_type"] = $post["duration_type"];
            $pledge["id_category"] = (int)$post["category"];


            if (isset($post["save_moderation"])) {
                $pledge["status"] = 2;
            }

            if (isset($post["save_unfinished"]) && Auth::isModel()) {
                $pledge["status"] = 0;
                $this->_helper->FlashMessenger->addMessage(notice("Pledge saved for later edit"));
            }


            if ($edit) {
                $this->pledge->update($pledge, "id=" . (int)$this->params["id_pledge"]);
            } else {
                $this->pledge->insert($pledge);
            }

            if ($pledge["id_video"] > 0) {
                $this->video->update(array("id_show" => $id_pledge), "id=" . $pledge["id_video"] . " AND type='pledge'");
                if ($this->_data["pledge"]->id_video != $pledge["id_video"] && !is_null($this->_data["pledge"]->id_video)) {
                    $this->video->update(array("id_show" => null), "id=" . $this->_data["pledge"]->id_video . " AND type='pledge'");
                }
            }
            /**
             * @todo need to do this another way due to race conditions ( not very probable, but possible)
             */
            $id_pledge = ($this->params["id_pledge"]) ?
                            $this->params["id_pledge"] :
                            (int)$this->pledge->getAdapter()->lastInsertId();

            if(is_null($_SESSION['user']['id_moderator'])) {
                $idModerator = 0;
            } else {
                $idModerator = $_SESSION['user']['id_moderator'];
            }
            if (isset($post["save_moderation"]) && $id_pledge) {
                $addNotification = array(
                    "id_from" => $id_model,
                    "type_from" => "model",
                    "id_to" => $idModerator,
                    "type_to" => "moderator",
                    "type" => "model-pledge-add",
                    "notification" => "Pledge submited for moderation",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "resource" => $id_pledge
                );

                $this->_helper->FlashMessenger->addMessage(notice("Pledge submited for moderation"));

                $this->addNotification($addNotification, "admin");
                $this->addNotification($addNotification, "moderator");
            }
            $this->_helper->FlashMessenger->addMessage(notice('Pledge saved!'));
            //photo upload

            /* if($post["galleries"]){
                 //foreach($post["galleries"] as $gallery){
                     $this->albums->update(array("id_resource" => $id_pledge),new Zend_Db_Expr("id=".(int)$post["galleries"]));
                 //}
             }  */

            if (isset($id_pledge) && isset($_FILES["cover_photo"]) && !empty($_FILES["cover_photo"]["name"])) {

                $upload = $this->upload->uploadPhoto($photo_dir, 'cover_photo');

                if ($upload['status'] == 'success') {

                    $filename = $upload['file'];
                    $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 400, config()->photo_watermark);
                    //$this->upload->create_square_image($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_t'.substr($filename,-4), 190);
                    //$this->upload->resize_image_proportional($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_tt'.substr($filename,-4), 190, 140);
                    $this->upload->resize_image_proportional($photo_dir . $filename, $photo_dir . substr($filename, 0, -4) . '_t' . substr($filename, -4), 190, 140, 1);

                    $this->load("photos");
                    $this->photos->addPhoto(
                        array(
                            'reference_id' => $id_pledge,
                            'entity' => \Images\Entity\PledgeImage::class,
                            'user' => $_SESSION['user']['id'],
                            'filename' => $upload['nested'].$filename,
                            'status' => Status::ACTIVE,
                            'type' => 'pledge_cover'
                        )
                    );

                } else {
                    echo 'error uploading photo!';
                }
            }

            // video upload
            if (isset($_FILES["cover_video"]) && !empty($_FILES["cover_video"]["name"])) {
                $video_dir = APPLICATION_PATH . '/../../public/uploads/videos/';


                $upload = $this->upload->uploadVideo($video_dir, 'cover_video');

                if ($upload['status'] == 'success') {
                    $vid["filename"] = $filename = $upload['file'];
                    $vid["id_model"] = $_SESSION["user"]["id"];
                    $vid["added"] = time();

                    $vid["active"] = 0;
                    $vid["state"] = 0;
                    $vid["type"] = 'pledge';


                    $this->load("video");
                    $id_video = $this->video->insert($vid);


                    $this->load("model_actions");
                    $this->model_actions->actionAdd("new_video", $id_model);

                    if ($id_video)
                        $this->pledge->update(array("id_video" => $id_video), $this->pledge->getAdapter()->quoteInto("id=?", (int)$id_pledge));

                    $this->_helper->FlashMessenger->addMessage(notice("Video uploaded! It will be moderated soon"));

                    //move_uploaded_file($_FILES['upload_video']['tmp_name'], "");
                } else {
                    $this->_helper->FlashMessenger->addMessage(notice("Upload video failed! \n" . $upload["message"]));
                }
            }

            if ($this->layout_control == "performer") $redirect_url = $this->view->url(array(), "pledge-list-performer-backend");
            elseif ($this->layout_control == "moderator") $redirect_url = $this->view->url(array("id_model" => $this->params["id_model"], "name" => $this->params["name"]), "pledge-list-moderator-backend");
            $this->_redirect($redirect_url);

        }

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("pledge-add-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));

    }

    public function deletepledgeAction()
    {
        if ($this->params["id_pledge"]) {
            $this->load("pledge");
            $this->pledge->deletePledge($this->params["id_pledge"], $this->load("photos"));
            $msg = "Pledge deleted";
        } else {
            $msg = "Pledge not deleted";
        }
        $this->_helper->FlashMessenger->addMessage(notice($msg));
        $this->_redirect($this->view->url(array(), "pledge-list-performer-backend"));
    }

    public function moderatepledgeAction()
    {
        /* moderation */
        if (!Auth::isModerator()) {
            $this->_redirect("/404");
        } else {
            $post = $this->_request->getPost();

            if ($post) {

                $this->load("pledge");
                $this->load("photos");

                $id_pledge = (int)$this->params["id_pledge"];
                $this->_data["pledge"] = $this->pledge->getById($id_pledge);

                if (isset($post["denyButton"])) {
                    $this->pledge->update(array("status" => -1), "id=" . $id_pledge);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["pledge"]->id_model,
                        "type_to" => "model",
                        "type" => "deny_pledge",
                        "notification" => 'Pledge "' . $this->_data["pledge"]->title . '" denied',
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $id_pledge
                    );
                    $this->addNotification($addNotification, $this->_data["pledge"]->id_model);

                    $this->photos->updateStatus(-1, $id_pledge, $this->_data["pledge"]->id_model, "pledge");
                    $msg = "Pledge denied";
                    $this->_helper->FlashMessenger->addMessage(notice($msg, false));
                }
                if (isset($post["acceptButton"])) {
                    $this->pledge->update(array("status" => 1), "id=" . $id_pledge);

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["pledge"]->id_model,
                        "type_to" => "model",
                        "type" => "approve_pledge",
                        "notification" => 'Pledge "' . $this->_data["pledge"]->title . '" approved',
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $id_pledge
                    );
                    $this->addNotification($addNotification, $this->_data["pledge"]->id_model);
                    $this->photos->updateStatus(1, $this->_data["pledge"]->id, $this->_data["pledge"]->id_model, "pledge");

                    $msg = "Pledge accepted";
                    $this->_helper->FlashMessenger->addMessage(notice($msg));

                    // add model action
                    $this->load("model_actions");
                    $this->model_actions->actionAdd("pledge", $this->_data["pledge"]->id_model);
                }


                $this->_redirect($this->view->url(
                    array(
                        "id_model" => $this->_data["pledge"]->id_model,
                        "id_pledge" => $id_pledge,
                        "name" => $this->params["name"],
                    ),
                    "pledge-edit-moderator-backend"
                ));
            }
        }
    }

    public function contributepledgeAction()
    {
        if ($this->layout_control != "frontend") $this->_redirect("/404");
        if (!isset($this->params["id_pledge"])) $this->_redirect("/404");

        $this->load("pledge");
        $this->load("pledge_perk");
        $id_perk = (int)$this->params["id_perk"];
        $id_pledge = (int)$this->params["id_pledge"];

        $this->_data['pledge'] = $this->pledge->getById($id_pledge);
        $this->_data["perks_model"] = $this->pledge_perk->getByIdPledge($this->_data["pledge"]->id, true, "model");
        $this->_data["perks_moderator"] = $this->pledge_perk->getByIdPledge($this->_data["pledge"]->id, true, "moderator");

        if ($this->_data['pledge']->start_date > time() || $this->_data['pledge']->end_date < time() || $this->_data["pledge"]->status != 1) {
            $this->_redirect("/404");
        }

        if ($id_perk) {
            $this->_data["selected_perk"] = $this->pledge_perk->getById($id_perk);
            if ($this->_data["selected_perk"]->status != 1) $this->_redirect("/404");
        } else {
            $this->_data["selected_perk"] = null;
        }
        //p($this->params,1);

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("pledge-contribute-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
    }

    public function addphotosAction()
    {

        if (Auth::isModel()) $id_model = user()->id;
        else if ($this->params["id_model"]) $id_model = $this->params["id_model"];

        $this->load("upload");
        $this->load("photos");
        $this->load("pledge");

        $photo_dir = APPLICATION_PATH . '/../../public/uploads/photos/';

        $request = $this->_request;
        $post = $request->getPost();
        if ($post) {

            $upload = $this->upload->uploadPhoto($photo_dir, 'Filedata');
            //p($upload,1)   ;
            if ($upload['status'] == 'success') {
                $filename = $upload['file'];
                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 800, 600, config()->photo_watermark);
                $this->upload->resize_image_proportional($photo_dir . $filename, $photo_dir . substr($filename, 0, -4) . '_t' . substr($filename, -4), 190, 140, 1);

                $photo_add = array(
                    "id_model" => $_SESSION['user']['id'],
                    "filename" => $filename,
                    //"caption" => substr($post['Filename'],0,-4),
                    "reference_id" => $this->params["id_pledge"],
                    "entity" => 'pledge',
                    "active" => 0,
                    "type" => "pledge",
                    "added" => time(),
                );

                $id_img = $this->photos->insertItems($photo_add);


                $this->_helper->FlashMessenger->addMessage(notice("Photos saved"));

                $pledge = $this->pledge->getById($this->params["id_pledge"]);

                if ($pledge->status == 1) {
                    if (Auth::isModel()) {
                        $addNotification = array(
                            "id_from" => $id_model,
                            "type_from" => "model",
                            "id_to" => $_SESSION["user"]["id_moderator"],
                            "type_to" => "moderator",
                            "type" => "image",
                            "notification" => 'New image for pledge "' . $pledge->title,
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $id_img
                        );
                        $this->addNotification($addNotification, "admin");
                        $this->addNotification($addNotification, "moderator");
                    } else {
                        $addNotification = array(
                            "id_from" => user()->id,
                            "type_from" => "moderator",
                            "id_to" => $id_model,
                            "type_to" => "model",
                            "type" => "image",
                            "notification" => 'New image for pledge "' . $pledge->title,
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $id_img
                        );
                        $this->addNotification($addNotification, "performer");
                    }


                }
                //move_uploaded_file($_FILES['upload_video']['tmp_name'], "");
            }

            //$this->_redirect('/model/upload/video');

            exit;
        } else {

            // send data to view, load view
            $this->view->assign($this->_data);

            //load view
            $this->render("photos-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
        }

    }

    //add & list, delete
    public function addperkAction()
    {

        if (Auth::isModel()) $id_model = user()->id;
        else if ($this->params["id_model"]) $id_model = $this->params["id_model"];

        $this->load("photos");
        $this->load("pledge_perk");
        $this->load("pledge");
        $this->_data["pledge"] = $this->pledge->getById($this->params["id_pledge"]);


        if ($this->params["id_perk"]) {
            $edit = true;
            $this->_data["perk"] = $this->pledge_perk->getById($this->params["id_perk"], $this->params["id_pledge"]);
        } else {
            $edit = false;
        }

        $photo_dir = APPLICATION_PATH . '/../../public/uploads/photos/';

        $request = $this->_request;
        $post = $request->getPost();

        if ($post) {

            // delete perk & photos
            if (isset($post["multiple_delete"])) {
                if (!empty($post["multiple_select"])) {

                    $perkDeleteString = '';
                    $perkDeletion = $this->pledge_perk->multipleAction($post["multiple_select"], "select");
                    foreach ($perkDeletion as $perkDeleteItem)
                        if ($perkDeleteItem->id_photo > 0) $perkDeleteString .= $perkDeleteItem->id_photo . ',';


                    $this->pledge_perk->multipleAction($post["multiple_select"], "delete");
                    $this->photos->multipleAction($perkDeleteString, "delete");

                    $this->_helper->FlashMessenger->addMessage(notice("Perks deleted"));
                }
            }

            if (isset($post["save_unfinished"])) {
                if (empty($post["title"])) {
                    $this->_helper->FlashMessenger->addMessage(notice("Empty pledge title"));
                    $this->_redirect($this->view->url(array("id_pledge" => $this->params["id_pledge"]), "pledge-perk-add-performer-backend"));
                }

                $this->load("pledge");
                $pledge = $this->pledge->getById($this->params["id_pledge"]);

                $amount_chips = (int)$post["amount"] + ((int)$post["amount"] * ($pledge->goal_rep_share) / 100);

                $perk = array();
                $perk["title"] = $post["title"];
                if (!$edit) {
                    $perk["user_type"] = $_SESSION["group"];
                    $perk["id_user"] = user()->id;
                }
                $perk["amount"] = round($amount_chips, 0, PHP_ROUND_HALF_EVEN);
                $perk["description"] = $post["description"];
                $perk["estimated_delivery"] = strtotime($post["estimated_delivery"]);
                $perk["quantity"] = (int)$this->params["quantity"];
                $perk["shipping"] = $this->params["shipping"];
                $perk["id_pledge"] = $this->params["id_pledge"];

                if (Auth::isModel())
                    $perk["status"] = 2;
                else
                    $perk["status"] = 1;

                $this->load("pledge_perk");
                if (!$edit)
                    $id_perk = $this->pledge_perk->insert($perk);
                else
                    $id_perk = $this->pledge_perk->update($perk, db()->quoteInto("id=" . (int)$this->params["id_perk"]));

                if ($id_perk && isset($_FILES["photo"]) && !empty($_FILES["photo"]["name"])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/photos/';
                    $this->load('upload');

                    $upload = $this->upload->uploadPhoto($photo_dir, 'photo');


                    if ($upload['status'] == 'success') {

                        $filename = $upload['file'];
                        //$this->upload->resize_image($photo_dir.$filename, $photo_dir.$filename, 800, 600, config()->photo_watermark);
                        //$this->upload->create_square_image($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_t'.substr($filename,-4), 190);
                        //$this->upload->resize_image_proportional($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_tt'.substr($filename,-4), 190, 140);
                        $fileNameNew = $upload['nested'].substr($filename, 0, -4) . '_t' . substr($filename, -4);
                        $this->upload->resize_image_proportional($photo_dir . $filename, $photo_dir . $fileNameNew, 190, 140, 1);

                        $this->photos->addPhoto(array("user" => $_SESSION['user']['id'], "filename" =>
                            $fileNameNew, "status" => Status::ACTIVE, "type" => 'perk'));
                        $id_photo = db()->lastInsertId();

                        if ($id_photo)
                            $this->pledge_perk->update(array("id_photo" => $id_photo), $this->pledge->getAdapter()->quoteInto("id=?", (int)$id_perk));
                    } else {
                        echo 'error uploading photo!';
                    }
                }

                if ($pledge->status == 1) {
                    if (Auth::isModel()) {
                        $addNotification = array(
                            "id_from" => $id_model,
                            "type_from" => "model",
                            "id_to" => $_SESSION["user"]["id_moderator"],
                            "type_to" => "moderator",
                            "type" => "model-perk-add",
                            "notification" => "Perk submited for moderation",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $this->params["id_pledge"]
                        );

                        $this->addNotification($addNotification, "admin");
                        $this->addNotification($addNotification, "moderator");
                    } else {
                        $addNotification = array(
                            "id_from" => user()->id,
                            "type_from" => "moderator",
                            "id_to" => $id_model,
                            "type_to" => "model",
                            "type" => "model-perk-add",
                            "notification" => "Perk bonus added",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $this->params["id_pledge"]
                        );

                        $this->addNotification($addNotification, "performer");
                    }
                }


                $this->_helper->FlashMessenger->addMessage(notice("Perk saved. Add another one"));
            }
            if (Auth::isModel())
                $redirect_url = $this->view->url(array("id_pledge" => $this->params["id_pledge"]), "perk-add-performer-backend");
            elseif (Auth::isModerator())
                $redirect_url = $this->view->url(array("id_pledge" => $this->params["id_pledge"], "id_model" => $this->params["id_model"], "name" => $this->params["name"]), "perk-add-moderator-backend");
            $this->_redirect($redirect_url);
        }


        $this->load("pledge_perk");
        $status = null;
        $this->_data["perks_model"] = $this->pledge_perk->getByIdPledge($this->params["id_pledge"], $status, "model");
        $this->_data["perks_moderator"] = $this->pledge_perk->getByIdPledge($this->params["id_pledge"], $status, "moderator");

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("perk-add-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));

    }

    public function moderateperkAction()
    {
        /* moderation */
        //   p($this->params,1);
        if (!Auth::isModerator()) {
            $this->_redirect("/404");
        } else {
            $post = $this->_request->getPost();

            if ($post) {

                $this->load("pledge_perk");
                $this->load("pledge");

                $this->_data["pledge"] = $this->pledge->getById($this->params["id_pledge"]);

                if (isset($post["acceptButtonPerk"])) {
                    $this->pledge_perk->multipleAction($post["multiple_selectPerk"], "accept");


                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["pledge"]->id_model,
                        "type_to" => "model",
                        "type" => "approve",
                        "notification" => "Perk approved",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["pledge"]->id
                    );
                    $this->addNotification($addNotification, $this->_data["pledge"]->id_model);

                }
                if (isset($post["denyButtonPerk"])) {
                    $this->pledge_perk->multipleAction($post["multiple_selectPerk"], "deny");

                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_data["pledge"]->id_model,
                        "type_to" => "model",
                        "type" => "deny",
                        "notification" => "Perk denied",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["pledge"]->id
                    );
                    $this->addNotification($addNotification, $this->_data["pledge"]->id_model);
                }

                //$this->_redirect($this->view->url(array("id_pledge"=>$this->_data["pledge"]->id, "title" => ro_slug($this->_data["pledge"]->title)),"pledge"));
                $this->_redirect($this->view->url(array("id_pledge" => $this->_data["pledge"]->id, "name" => $this->params["name"], "id_model" => $this->params["id_model"]), "perk-add-moderator-backend"));
            }
        }
    }

    public function addupdateAction()
    {

        $this->load("pledge_update");

        if ($this->params["id_pledge"]) {
            //                        $this->_data["pledge"] = $this->pledge->getById($this->params["id_pledge"]);
            $request = $this->_request;
            $post = $request->getPost();

            if ($post) {
                $update["id_pledge"] = (int)$this->params["id_pledge"];
                $update["title"] = $post["title"];
                $update["description"] = $post["description"];
                $update["added"] = time();

                $this->pledge_update->insert($update);

                $this->_helper->FlashMessenger->addMessage(notice("Pledge update saved"));
                $this->_redirect($this->view->url(array("controler" => "model", "action" => "pledges", "type" => "update", "id_pledge" => $this->params["id_pledge"])));
            }
            $this->_data["updates"] = $this->pledge_update->getByIdPledge($this->params["id_pledge"]);

        }

        // send data to view, load view
        $this->view->assign($this->_data);

        //load view
        $this->render("update-add-" . $this->layout_control . ($this->layout_control == "frontend" ? "" : "-backend"));
    }
}
