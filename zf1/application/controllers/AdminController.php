<?php

use Application\Entity\Role;
use Application\Entity\User;

class AdminController extends App_Controller_Action
{
    protected $em = null;

    public function init()
    {

        parent::init();
        $action = $this->_request->action;


        $this->load("user_notifications");

        if (Auth::isModerator()) {
            $this->load("messages");

            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
            unset($this->messages);
        }

        $this->em = Zend_Registry::get('service_manager')->get('Doctrine\ORM\EntityManager');

        $this->user = Zend_Registry::get('service_manager')->get('zfcuser_auth_service')->getIdentity();

    }

    public function pwresetAction()
    {

        if ($_SESSION['user']['id']) $this->_redirect('/admin/');

        $this->load('moderator');
        $this->_data['var'] = $this->request->var;

        if ($this->request->var && $this->request->var != 'done') {
            $this->_data['user'] = $this->moderator->fetchRow($this->moderator->select()->where("reset_code=?", $this->request->var));
        }

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if ($post['submit'] == 'Submit' && $post['email']) {

                $get_user = $this->moderator->fetchRow($this->moderator->select()->where("email=?", $post['email']));

                if (!$get_user->email) {
                    $this->_helper->FlashMessenger->addMessage(notice("We could not find an account that matches that email", false));
                    $this->_redirect('/admin/pwreset/');
                }

                if ($get_user->active == 0) {
                    $this->_helper->FlashMessenger->addMessage(notice("You need to activate your account first.", false));
                    $this->_redirect('/admin/pwreset/');
                }

                $reset_code = md5(md5(microtime() . $get_user->email . 'n8&^W') . '-' . substr(md5('p8*&g+' . rand(11, 99999) . 'cu7^S'), 2, 14));

                $link = "http://" . strtolower(config()->site_name) . "/admin/pwreset/" . $reset_code;

                $this->load("templates");
                $tmpl = $this->templates->getContent("password_recovery");

                $message = $tmpl->content;
                $message = str_replace("{name}", $get_user->display_name, $message);
                $message = str_replace("{url}", $link, $message);
                $message = str_replace("{link}", '<a href="' . $link . '">click here</a>', $message);

                $mail = new Zend_Mail();
                $mail->setFrom('no-reply@' . strtolower(config()->site_name), config()->site_name);
                $mail->addTo($get_user->email);
                $mail->setSubject($tmpl->title);
                $mail->setBodyHtml($message);

                if ($mail->send()) {
                    $this->moderator->update(array("reset_code" => $reset_code), "id=" . $get_user->id);
                    $this->_helper->FlashMessenger->addMessage(notice("An email has been sent to <i>" . $get_user->email . "</i> that will allow you to reset your password"));
                    $this->_redirect('/admin/pwreset/done');
                } else {
                    $this->_helper->FlashMessenger->addMessage(notice("There was a problem, please try again", false));
                }

                //$this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            if ($post['reset'] == 'Reset' && $post['password'] != '' && $post['password'] == $post['confirm_password']) {

                if (!$this->_data['user']->id) {
                    $this->_helper->FlashMessenger->addMessage(notice("Invalid verification code", false));
                    $this->_redirect('/admin/pwreset/' . $this->request->var);
                }

                $this->moderator->update(array("password" => md5($post['password']), "reset_code" => ""), "id=" . $this->_data['user']->id);

                $this->load("notifications");
                $this->notifications->addNotification($this->_data['user']->id, "moderator", $this->_data['user']->id, "moderator", "password-reset", ($this->_data['user']->id == 0 ? "admin - password reset" : "moderator - password reset"), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice("Your password successfully changed. You can procede to login now.", true));
                $this->redirectToLogin('admin', $this->getRequest()->getRequestUri());
            }

            $this->_redirect('/admin/pwreset/');
        }

    }

    public function indexAction()
    {


    }

    public function chatsettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "chat-settings", "view")) return $this->_forward("restrict");

        $this->load('model');
        $this->load('rates');
        $this->load('rates_limits');
        $this->load('chips_packages');

        $this->_data['rates_min'] = $this->rates_limits->getLimitsByType('min');
        $this->_data['rates_max'] = $this->rates_limits->getLimitsByType('max');
        $this->_data['rates_default'] = $this->rates_limits->getLimitsByType('default');

        $this->_data["packages"] = $this->chips_packages->fetchAll($this->chips_packages->select()->order("chips ASC"));

        //modif !! pt rates limits
        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            //save cost for 1 minute call
            if ($this->_request->save_call_cost == "Save call cost") {
                $this->load("config");
                $this->config->update(array("val" => (int)$this->_request->call_cost), "var='call_cost'");
            }

            if ($this->_request->save_limits == 'Save prices') { // Save new rates limits
                unset($post['save_limits']);
                if (!$this->acl->isAllowed($_SESSION['group'], "chat-settings", "edit")) return $this->_forward("restrict");

                foreach ($post as $key => $val) {

                    $id = substr($key, 0, strpos($key, "_"));
                    $type = substr($key, strpos($key, "_") + 1);

                    $_rates = $this->rates_limits->getLimit($id, $type);

                    if ($_rates->id_rate) { //already have a value

                        if ($_rates->value != $val) { // request has new value

                            db()->query("update rates_limits set " . db()->quoteInto("value=?", $val) . " where " . db()->quoteInto("limit_type=?", $type) . " and " . db()->quoteInto("id_rate=?", $id));

                        }

                    } else { //no request sent yet

                        db()->query("insert into rates_limits set " . db()->quoteInto("value=?", $val) . ", " . db()->quoteInto("limit_type=?", $type) . "," . db()->quoteInto("id_rate=?", $id));

                    }

                }
                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "chat-limits-edit", (user()->id == 0 ? "admin - chat rates limits changed" : "moderator - chat rates limits changed"), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice("Price limits saved!"));
            }

            if ($this->_request->save_package == 'Save package') { // Save new rates limits

                if (empty($post["name"])) {
                    $this->_helper->FlashMessenger->addMessage(notice("Enter package name!", 2));
                    $this->_redirect($this->view->url(array(), "chat-settings"));
                }

                unset($post['save_package']);
                if (!$this->acl->isAllowed($_SESSION['group'], "all_resources", "view")) return $this->_forward("restrict");

                if (!$post["chips"] == 0 && !$post["bonus"] == 0)
                    $post["rate"] = $post["amount"] / ($post["chips"] + $post["bonus"]);

                if (empty($post["id"]) || !is_numeric($post["id"])) {
                    unset($post["id"]);
                    $this->chips_packages->insert($post);
                } else
                    $this->chips_packages->update($post, array("id=?" => $post["id"]));

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "chips-package-added", (user()->id == 0 ? "admin - chips package added" : "moderator - chips package added"), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice("Package saved!"));
            }

            if ($this->_request->mark_delete == 'Delete') {
                $post["multiple_select"] = trim($post["multiple_select"], ",");
                $this->chips_packages->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '" . $post["multiple_select"] . "') > 0"));
            }

            $this->_redirect($this->view->url(array(), "chat-settings"));
        }
    }

    public function modelsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "models", "view")) return $this->_forward("restrict");

        $this->load('model');
        $state = 0;
        switch ($this->_request->type) {
            case 'all':
                $state = 2;
                break;
            case 'active':
                $state = 1;
                break;
            case 'pending':
                $state = 0;
                break;
            case 'denied':
                $state = -1;
                break;

        }

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


        $userRepo = $this->em->getRepository("Application\\Entity\\User");

        $this->_data["userRepo"] = $userRepo;

        $paginator = Zend_Paginator::factory(
            $state == 2 ? $this->model->fetchAllModels() : $userRepo->getPerformers(false, $state)
        );
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $this->_data['type'] = $this->_request->type;
    }

    public function moderatorsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "moderators", "view")) return $this->_forward("restrict");

        $this->load('moderator');
        $type = 0;
        switch ($this->_request->type) {
            case 'all':
                $type = 2;
                break;
            case 'active':
                $type = 1;
                break;
            case 'pending':
                $type = 0;
                break;
            case 'denied':
                $type = -1;
                break;

        }

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

        $paginator = Zend_Paginator::factory(($type == 2 ? $this->moderator->fetchAll() : $this->moderator->getModerators($type)));
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $this->_data['type'] = $this->_request->type;
    }

    public function usersAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "users", "view")) return $this->_forward("restrict");

        $this->load('user');
        $type = 0;
        switch ($this->_request->type) {
            case 'all':
                $type = 2;
                break;
            case 'active':
                $type = 1;
                break;
            case 'pending':
                $type = 0;
                break;
            case 'denied':
                $type = -1;
                break;

        }

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

        $paginator = Zend_Paginator::factory(($type == 2 ? $this->user->fetchAll() : $this->user->getUsers($type)));
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $this->_data['type'] = $this->_request->type;
    }

    public function modelsettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "model-settings", "view")) return $this->_forward("restrict");

        $this->load('info');
        $this->load('countries');
        $this->load('model_info');
        $this->load('model');
        $this->load('categories');
        $this->load('model_websites');
        $this->load('messages');

        $this->_data['info_fields'] = $this->model_info->getInfoByModel($this->_request->id);
        $this->_data['countries'] = $this->countries->fetchAllLocations('co');
        $this->_data['categories'] = $this->categories->fetchAll($this->categories->select()->where("entity=?", User::class));

        //$this->_data['model'] = $this->model->find((int)$this->_request->id)->current();
        $this->_data['model'] = $this->model->getModel((int)$this->_request->id);
        $this->_data['cats_to_model'] = $this->categories->getCategoriesByModel($this->_data['model']->id);

        switch ($this->_request->manage) {
            case 'edit':

                if ($this->request->isPost()) {


                    if (!$this->acl->isAllowed($_SESSION['group'], "model-settings", "edit")) return $this->_forward("restrict");

                    $post = $this->_request->getPost();


                    //$website = array("id_model" => $this->_data["model"]->id, "url" => $post["website_url"], "title" => $post["website_title"]);

                    //$this->model_websites->addWebsite($website);

                    //unset($post["website_url"]);
                    //unset($post["website_title"]);


                    if ($this->_request->save == 'Save') { // Main profile


                        //approve cover photos
                        if ($post["PhotoCoverPending"]) {

                            $p_id = $post["PhotoCoverPending"];
                            $this->_data["model"]->activateDocument("cover", $p_id, "pending");

                            if ($p_id != "all") {
                                $addNotification = array(
                                    "id_from" => $_SESSION["user"]["id"],
                                    "type_from" => $_SESSION["group"],
                                    "id_to" => $this->_data['model']->id,
                                    "type_to" => "model",
                                    "type" => "approve_photo",
                                    "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " approved your cover photo",
                                    "ip" => $_SERVER["REMOTE_ADDR"],
                                    "date" => time(),
                                    "resource" => $p_id
                                );

                                $this->addNotification($addNotification, "model");

                            } else {
                                $addNotification = array(
                                    "id_from" => $_SESSION["user"]["id"],
                                    "type_from" => $_SESSION["group"],
                                    "id_to" => $this->_data['model']->id,
                                    "type_to" => "model",
                                    "type" => "reject_photo",
                                    "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " rejected your cover photo",
                                    "ip" => $_SERVER["REMOTE_ADDR"],
                                    "date" => time(),
                                    "resource" => 0
                                );

                                $this->addNotification($addNotification, "model");
                            }
                            unset($post["PhotoCoverPending"]);
                            unset($p_id);
                        }

                        if ($post["PhotoCoverRejected"]) {
                            $p_id = $post["PhotoCoverRejected"];

                            $deleted = $this->_data["model"]->getAllPhotoCover("rejected");

                            if ($this->_data["model"]->deleteDocuments("cover", $p_id)) {

                                foreach ($deleted as $k => $del) {
                                    if ($k == $p_id || $p_id == "all")
                                        unlink(ltrim($del["file"], '/'));
                                }
                            }

                            unset($post["PhotoCoverRejected"]);
                            unset($p_id);
                        }
                        unset($post["PhotoCoverPending"]);
                        unset($post["PhotoCoverRejected"]);
                        // end approve cover photos


                        if (isset($_FILES['cover']) && is_uploaded_file($_FILES['cover']['tmp_name'])) {
                            $photo_dir = APPLICATION_PATH . '/../../public/uploads/photos/';
                            $this->load('upload');

                            $upload = $this->upload->uploadPhoto($photo_dir);

                            if ($upload['status'] == 'success') {
                                $filename = $upload['file'];
                                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 260, 190);

                                $current_cover = $this->_data['model']->getCover(1);

                                if ($current_cover->id) {
                                    //delete old cover file and remove from db
                                    db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                                    unlink(APPLICATION_PATH . '/../../public/uploads/photos/' . $current_cover->filename);
                                }

                                //save new  cover in db tbl photos,
                                db()->query("insert into photos set " . db()->quoteInto("id_model=?", $this->_data['model']->id) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='cover' ");
                                //update db tlb model --> id_cover
                                $post['id_cover'] = $this->db->lastInsertId();

                            } else {
                                $photo_upload_failed = "<br>" . $upload['message'];
                            }
                        } else {
                            //delete cover
                            if ($post['delete_cover']) {
                                $current_cover = $this->_data['model']->getCover(1);
                                if ($current_cover->id) {
                                    db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                                    unlink(APPLICATION_PATH . '/../../public/uploads/photos/' . $current_cover->filename);
                                }
                            }
                        }

                        $post['birthday'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
                        unset($post['birthday_year']);
                        unset($post['birthday_month']);
                        unset($post['birthday_day']);
                        unset($post['save']);
                        unset($post['cover']);
                        unset($post['delete_cover']);

                        $this->categories->addCategoryForModel($this->_data['model']->id, array($post['category'], $post['category1'], $post['category2']));
                        unset($post['category']);
                        unset($post['category1']);
                        unset($post['category2']);


                        if ($post["approve_screen_name"]) {
                            $post['new_screen_name'] = '';
                            $approved = true;
                        } else {
                            $approved = false;
                            unset($post['new_screen_name']);
                        }
                        unset($post['approve_screen_name']);


                        unset($post['country_name']);
                        unset($post['region_name']);
                        unset($post['city_name']);

                        $this->model->update($post, $this->model->getAdapter()->quoteInto("id=?", $this->_request->id));

                        if ($approved) {
                            //new screen name approved
                            $addNotification = array(
                                "id_from" => $_SESSION["user"]["id"],
                                "type_from" => $_SESSION["group"],
                                "id_to" => $this->_data["model"]->id,
                                "type_to" => "model",
                                "type" => "screen_name",
                                "notification" => "New screen name approved",
                                "ip" => $_SERVER["REMOTE_ADDR"],
                                "date" => time(),
                                "resource" => $this->_data["model"]->id
                            );
                            $this->addNotification($addNotification, "model");
                        }

                        $message = $this->_data['model']->first_name . " " . $this->_data['model']->name . "'s profile has been successfully saved!" . $photo_upload_failed;
                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-profile-edit", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                        $this->_helper->FlashMessenger->addMessage(notice($message));
                    }

                    if ($this->_request->save2 == 'Save') { // More information about me
                        unset($post['save2']);
                        foreach ($post as $key => $val) {
                            $_info = $this->model_info->getModelInfoById($this->_request->id, $key);

                            if (!$val || $val == '') { //if the field is empty delete from db if it exists
                                $this->model_info->delete("id_model=" . $this->_request->id . " and id_field=" . $key);
                            } else {
                                if ($_info->id_field) { //we have previous values
                                    $this->model_info->update(array("value" => $val), "id_model=" . $this->_request->id . " and id_field=" . $key);

                                } else { //we insert new value
                                    $this->model_info->insert(array("value" => $val, "id_model" => $this->_request->id, "id_field" => $key));
                                }
                            }
                        }

                        $message = $this->_data['model']->first_name . " " . $this->_data['model']->name . "'s information successfully saved!";

                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-profile-edit", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                        $this->_helper->FlashMessenger->addMessage(notice($message));
                    }

                    $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-model"));
                }
                break;

            case 'approve':

                if (!$this->acl->isAllowed($_SESSION['group'], "model-settings", "edit")) return $this->_forward("restrict");
                $this->load("user");

                $this->user->setActive($this->_request->id, true);
                $response = $this->model->setActive($this->_request->id, true);
                if ($response) {
                    $this->load('model_rates');

                    //set chat rates if none found
                    $this->model_rates->initRatesByModel($this->_request->id);
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " approved!"));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-account-manage", (user()->id == 0 ? "admin - " . $this->_request->name . " approved!" : "moderator - " . $this->_request->name . " approved!"), 1, getUserIp());


                    //notification message
                    $this->load("templates");
                    $tmpl_mes = $this->templates->getContent("model_account_approval_message");
                    $tmpl = $this->templates->getContent("model_account_approval");
                    if ($tmpl_mes) {
                        $tmpl_mes->content = str_replace("{name}", $this->_data["model"]->screen_name, $tmpl_mes->content);

                        $message["id_sender"] = 0;
                        $message["sender_type"] = "moderator";
                        $message["id_receiver"] = $this->_request->id;
                        $message["receiver_type"] = "model";
                        $message["subject"] = !empty($tmpl_mes->title) ? $tmpl_mes->title : "Welcome";
                        $message["message"] = !empty($tmpl_mes->content) ? $tmpl_mes->content : "Welcome";
                        $message["inbox"] = 1;
                        $message["outbox"] = 0;
                        $message["send_date"] = time();
                        $message["read"] = 1;
                        $message["tip"] = 0;
                        $message["type"] = "inbox";

                        $this->load("messages");
                        $this->messages->insert($message);
                    }
                    $addNotification = array(
                        "id_from" => $_SESSION['user']['id'],
                        "type_from" => $_SESSION['group'],
                        "id_to" => $this->_request->id,
                        "type_to" => "model",
                        "type" => "new_message",
                        "notification" => $tmpl->title ? $tmpl->title : "Welcome",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "read" => "0",
                        "date" => time(),
                        "resource" => $this->messages->getAdapter()->lastInsertId()
                    );
                    $this->addNotification($addNotification, $this->_request->id);

                    //email notification
                    //send email
                    $link = "http://" . strtolower(config()->site_name) . "/performer/login";


                    $message = $tmpl->content;
                    $message = str_replace("{name}", $this->_data['model']->screen_name, $message);
                    $message = str_replace("{url}", $link, $message);
                    $message = str_replace("{link}", '<a href="' . $link . '">click here</a>', $message);

                    $mail = new Zend_Mail();
                    $mail->setFrom('no-reply@' . strtolower(config()->site_name), config()->site_name);
                    $mail->addTo($this->_data['model']->email);
                    $mail->setSubject($tmpl->title);
                    $mail->setBodyHtml($message);

                    if ($mail->send()) {
                        $this->_helper->FlashMessenger->addMessage(notice("An email has been sent to <i>" . $this->_data['model']->email . "</i> "));
                    } else {
                        $this->_helper->FlashMessenger->addMessage(notice("There was a problem, mail not sent", false));
                    }

                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'active'), "show-model"));
                break;

            case 'deny':

                if (!$this->acl->isAllowed($_SESSION['group'], "model-settings", "edit")) return $this->_forward("restrict");

                $response = $this->model->setActive($this->_request->id, false);
                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " denied!"));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-account-manage", (user()->id == 0 ? "admin - " . $this->_request->name . " denied!" : "moderator - " . $this->_request->name . " denied!"), 1, getUserIp());
                    //send mail - TO DO
                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'denied'), "show-model"));
                break;

            case 'delete':

                if (!$this->acl->isAllowed($_SESSION['group'], "model-settings", "edit")) return $this->_forward("restrict");

                //delete permissions
                $this->load("permissions");
                $this->permissions->deletePermissionsForUser($this->_request->id, "model");

                //delete settings
                $this->load("user_settings");
                $this->user_settings->deleteFieldsByUser($this->_request->id, "model");

                //delete from wall - for other models - TO DO

                $response = $this->model->delete(db()->quoteInto("id=?", $this->_request->id));
                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " deleted!"));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "model-account-manage", (user()->id == 0 ? "admin - " . $this->_request->name . " deleted!" : "moderator - " . $this->_request->name . " deleted!"), 1, getUserIp());

                    //send mail - TO DO
                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'pending'), "show-model"));
                break;

            default:
                $this->_helper->FlashMessenger->addMessage(notice("Illegal opperation!"));
                $this->_redirect($this->view->url(array("type" => 'pending'), "show-model"));
                break;
        }

    }

    public function usersettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "user-settings", "view")) return $this->_forward("restrict");

        $this->load('timezones');
        $this->_data['timezones'] = $this->timezones->fetchAll();

        $this->load('countries');
        $this->load('user');
        $this->_data['countries'] = $this->countries->fetchAllLocations('co');

        $this->_data['user'] = $this->user->find((int)$this->_request->id)->current();

        switch ($this->_request->manage) {
            case 'edit':

                if ($this->request->isPost()) {

                    if (!$this->acl->isAllowed($_SESSION['group'], "user-settings", "edit")) return $this->_forward("restrict");

                    $post = $this->_request->getPost();
                    if ($this->_request->save == 'Save') { // Main profile

                        if (isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                            $photo_dir = APPLICATION_PATH . '/../../public/uploads/user/';
                            $this->load('upload');

                            $upload = $this->upload->uploadPhoto($photo_dir, "avatar");

                            if ($upload['status'] == 'success') {
                                $filename = $upload['file'];
                                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 260, 190);
                                $post['avatar'] = $filename;

                            } else {
                                $photo_upload_failed = "<br>" . $upload['message'];
                            }
                        }

                        $post['birthday'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
                        unset($post['_birthday']);
                        unset($post['birthday_year']);
                        unset($post['birthday_month']);
                        unset($post['birthday_day']);
                        unset($post['save']);
                        unset($post['chips']);
                        unset($post['privacy']);

                        $this->user->update($post, $this->user->getAdapter()->quoteInto("id=?", $this->_request->id));

                        $message = $this->_data['user']->username . "'s profile has been successfully saved!" . $photo_upload_failed;
                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "user", "user-profile-edit", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                        $this->_helper->FlashMessenger->addMessage(notice($message));
                    }

                    $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-user"));
                }
                break;

            case 'approve':

                if (!$this->acl->isAllowed($_SESSION['group'], "user-settings", "edit")) return $this->_forward("restrict");

                $response = $this->user->setActive($this->_request->id, true);

                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " approved!"));

                    $message = $this->_request->name . " approved!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "user", "user-account-manage", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());
                    //send email -TO DO

                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'active'), "show-user"));
                break;

            case 'deny':

                if (!$this->acl->isAllowed($_SESSION['group'], "user-settings", "edit")) return $this->_forward("restrict");

                $response = $this->user->setActive($this->_request->id, false);
                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " denied!"));

                    $message = $this->_request->name . " denied!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "user", "user-account-manage", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());
                    //send mail - TO DO
                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'denied'), "show-user"));
                break;

            case 'delete':

                if (!$this->acl->isAllowed($_SESSION['group'], "user-settings", "edit")) return $this->_forward("restrict");

                $userRepo = $this->em->getRepository('Application\Entity\User');
                $user = $userRepo->findOneBy(array("id" => $this->_request->id));

                $this->em->remove($user);
                $this->em->flush();
                //delete settings
                //$this->load("user_settings");
                //$this->user_settings->deleteFieldsByUser($this->_request->id, "user");

                //$response = $this->user->delete(db()->quoteInto("user_id=?", $this->_request->id));
                //if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " deleted!"));

                    $message = $this->_request->name . " deleted!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "user-account-manage", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                    //send mail - TO DO
               // } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'pending'), "show-user"));
                break;
            default:
                $this->_helper->FlashMessenger->addMessage(notice("Illegal opperation!"));
                $this->_redirect($this->view->url(array("type" => 'pending'), "show-user"));
                break;
        }


    }

    public function moderatorsettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "moderator-settings", "view")) return $this->_forward("restrict");

        $this->load('countries');
        $this->load('moderator');
        $this->_data['countries'] = $this->countries->fetchAllLocations('co');

        $this->_data['moderator'] = $this->moderator->find((int)$this->_request->id)->current();

        switch ($this->_request->manage) {
            case 'edit':

                if ($this->request->isPost()) {

                    if (!$this->acl->isAllowed($_SESSION['group'], "moderator-settings", "edit")) return $this->_forward("restrict");

                    $post = $this->_request->getPost();

                    if ($this->_request->save == 'Save') { // Main profile
                        unset($post['save']);
                        if (isset($_FILES['cover']) && is_uploaded_file($_FILES['cover']['tmp_name'])) {
                            $photo_dir = APPLICATION_PATH . '/../../public/uploads/photos/';

                            $this->load('upload');
                            $upload = $this->upload->uploadPhoto($photo_dir);

                            if ($upload['status'] == 'success') {
                                $filename = $upload['file'];

                                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 260, 190);

                                $current_cover = $this->_data['moderator']->getCover(1);

                                if ($current_cover->id) {
                                    //delete old cover file and remove from db
                                    db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                                    unlink(APPLICATION_PATH . '/../../public/uploads/photos/' . $current_cover->filename);
                                }

                                //save new  cover in db tbl photos,
                                db()->query("insert into photos set " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='cover' ");
                                //update db tlb model --> id_cover
                                $post['id_cover'] = $this->db->lastInsertId();

                            } else {
                                $photo_upload_failed = "<br>" . $upload['message'];
                            }
                        } else {
                            //delete cover
                            if ($post['delete_cover']) {
                                $current_cover = $this->_data['moderator']->getCover(1);
                                if ($current_cover->id) {
                                    db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                                    unlink(APPLICATION_PATH . '/../../public/uploads/photos/' . $current_cover->filename);

                                }
                            }
                        }
                        unset($post["cover"]);
                        unset($post["delete_cover"]);

                        $arrContact = array(
                            "contact1" => array(
                                "name" => $post["other_contact1"],
                                "value" => ($post["other_contact1"] ? $post["other_id1"] : "")
                            ),
                            "contact2" => array(
                                "name" => $post["other_contact2"],
                                "value" => ($post["other_contact2"] ? $post["other_id2"] : "")
                            ),
                            "contact3" => array(
                                "name" => $post["other_contact3"],
                                "value" => ($post["other_contact3"] ? $post["other_id3"] : "")
                            ),
                        );


                        unset($post["other_contact1"]);
                        unset($post["other_contact2"]);
                        unset($post["other_contact3"]);
                        unset($post["other_id1"]);
                        unset($post["other_id2"]);
                        unset($post["other_id3"]);

                        $post["other_contact"] = json_encode($arrContact);

                        $this->moderator->update($post, $this->moderator->getAdapter()->quoteInto("id=?", $this->_request->id));

                        $message = $this->_data['moderator']->username . "'s profile has been successfully saved!";
                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "moderator", "moderator-profile-edit", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                        $this->_helper->FlashMessenger->addMessage(notice($message));
                    }

                    $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-moderator"));
                }
                break;

            case 'approve':

                if (!$this->acl->isAllowed($_SESSION['group'], "moderator-settings", "edit")) return $this->_forward("restrict");

                $response = $this->moderator->setActive($this->_request->id, true);
                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " approved!"));

                    $message = $this->_request->name . " approved!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "moderator", "moderator-account-manage", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                    //send email -TO DO

                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'active'), "show-moderator"));
                break;

            case 'deny':

                if (!$this->acl->isAllowed($_SESSION['group'], "moderator-settings", "edit")) return $this->_forward("restrict");

                $response = $this->moderator->setActive($this->_request->id, false);
                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " denied!"));

                    $message = $this->_request->name . " denied!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "moderator", "moderator-account-manage", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                    //send mail - TO DO
                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'denied'), "show-moderator"));
                break;

            case 'delete':

                if (!$this->acl->isAllowed($_SESSION['group'], "moderator-settings", "edit")) return $this->_forward("restrict");

                //delete permissions
                $this->load("permissions");
                $this->permissions->deletePermissionsForUser($this->_request->id, "moderator");

                $response = $this->moderator->delete(db()->quoteInto("id=?", $this->_request->id));
                if ($response) {
                    $this->_helper->FlashMessenger->addMessage(notice($this->_request->name . " deleted!"));

                    $message = $this->_request->name . " deleted!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "moderator-account-manage", (user()->id == 0 ? "admin - " . $message : "moderator - " . $message), 1, getUserIp());

                    //send mail - TO DO
                } else $this->_helper->FlashMessenger->addMessage(notice("Action failed! Please try again!"));

                $this->_redirect($this->view->url(array("type" => 'pending'), "show-moderator"));
                break;
            default:
                $this->_helper->FlashMessenger->addMessage(notice("Illegal opperation!"));
                $this->_redirect($this->view->url(array("type" => 'pending'), "show-moderator"));
                break;
        }

    }

    public function modelaccountsettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "model-account-settings", "view")) return $this->_forward("restrict");

        $this->load('info');
        $this->load('countries');
        $this->load('model_info');
        $this->load('model');
        $this->load('permissions');
        $this->load('timezones');
        //$this->load("user_notifications");
        $this->load("photos");
        $this->load("moderator");
        $this->load("model_moderator");
        $this->load("model_websites");

        $this->_data['timezones'] = $this->timezones->fetchAll();
        $this->_data['info_fields'] = $this->model_info->getInfoByModel($this->_request->id);
        //$this->_data['countries'] = $this->countries->fetchAllLocations('co');

        $this->_data["moderators"] = $this->moderator->getModeratorsArray();


        $perms = $this->permissions->getPermissionsForUser($this->_request->id, 'model');
        if ($perms) $perms = $perms->toArray();
        else $perms = array();
        $permissions = array();
        if (count($perms) > 0) {
            foreach ($perms as $perm) {
                $types = explode(",", $perm['permission']);
                foreach ($types as $type) {
                    $permissions[$perm['action'] . "_" . $type] = true;
                }
            }
        }


        $this->_data['permissions'] = $permissions;

        //$this->_data['model'] = $this->model->find((int)$this->_request->id)->current();
        $this->_data['model'] = $this->model->getModel((int)$this->_request->id);

        if ($this->_data['model']->country > 0) {
            $this->_data['country'] = $this->countries->getLocationById($this->_data['model']->country);
        } else {
            $this->_data['country'] = '';
        }

        if ($this->_data['model']->region > 0) {
            $this->_data['region'] = $this->countries->getLocationById($this->_data['model']->region);
        } else {
            $this->_data['region'] = '';
        }

        if ($this->_data['model']->city > 0) {
            $this->_data['city'] = $this->countries->getLocationById($this->_data['model']->city);
        } else {
            $this->_data['city'] = '';
        }

        if ($this->request->isPost()) {

            $post = $this->_request->getPost();

            if ($this->_request->save && !$this->acl->isAllowed($_SESSION['group'], "model-account-settings", "edit")) return $this->_forward("restrict");

            if ($this->_request->save == 'Save') { // model account info
                $notice_msq = $this->_request->name . "'s account settings have been successfully saved!";

                if (!$post['password'] || $post['password'] != $post['confirm_password']) {
                    unset($post['password']);
                    if ($post['password'] != $post['confirm_password']) $notice_msq = "Your confirmation password didn't match! Try again.";
                } else {
                    $post['password'] = md5($post['password']);
                }


                //file uploads
                $this->load('upload');
                $file_upload_failed = '';

                if($post["auto_approve"]) {
                    $autoapprove = $post['auto_approve'];

                    $this->_data["model"]->setAutoApprove($autoapprove);
                    $addNotification = array(
                        "id_from" => $_SESSION["user"]["id"],
                        "type_from" => $_SESSION["group"],
                        "id_to" => $this->_data['model']->id,
                        "type_to" => "model",
                        "type" => "auto_approve_photos",
                        "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " has set your photos status to auto approve!",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => 0
                    );
                    $this->addNotification($addNotification, "model");
                }
                if ($post["photoIdPending"]) {

                    $p_id = $post["photoIdPending"];

//                    db()->query("update photos set active=1 where ".db()->quoteInto("id=?", $p_id));

//                    $photos = $this->_data['model']->getAllPhotoIds();

//                    foreach( $photos as $k=>$ph ){
//                        if($k != $p_id)
//                        unlink(ltrim($ph["file"],'/'));
//                    }
                    //db()->delete("photos", " id_model=".$this->_data["model"]->id." AND type='photo_id' AND id !=".$p_id);
                    $this->_data["model"]->activateDocument("photo_id", $p_id, "pending");

                    if ($p_id != "all") {
                        /*                                    $this->user_notifications->addNotification(
                                                        $id_from = $_SESSION["user"]["id"],
                                                        $from_type = $_SESSION["group"],
                                                        $id_to = $this->_data['model']->id,
                                                        $to_type = "model",
                                                        $type="upload_doc",
                                                        $notification = "Moderator appoved your photo id",
                                                        $read = 0,
                                                        $_SERVER["REMOTE_ADDR"],
                                                        $resource = $p_id
                                                    ) ; */

                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "approve_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " appoved your photo id",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $p_id
                        );
                        $this->addNotification($addNotification, "model");

                    } else {
                        //                           $this->user_notifications->addNotification(
//                                                            $id_from = $_SESSION["user"]["id"],
//                                                            $from_type = $_SESSION["group"],
//                                                            $id_to = $this->_data["model"]->id,
//                                                            $to_type = "model",
//                                                            $type="upload_doc",
//                                                            $notification = "Moderator rejected your photo id",
//                                                            $read = 0,
//                                                            $_SERVER["REMOTE_ADDR"],
//                                                            $resource = 0
//                                                        ) ;
                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "reject_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " rejected your photo id",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => 0
                        );

                        $this->addNotification($addNotification, "model");
                    }

                    unset($post["photoIdPending"]);
                    unset($p_id);

                }

                if ($post["photoIdRejected"]) {
                    $p_id = $post["photoIdRejected"];

                    $deleted = $this->_data["model"]->getAllPhotoIds("rejected");
                    if ($this->_data["model"]->deleteDocuments("photo_id", $p_id)) {

                        foreach ($deleted as $k => $del) {
                            if ($k != $p_id)
                                unlink(ltrim($del["file"], '/'));
                        }
                    }


                    unset($post["photoIdRejected"]);
                    unset($p_id);
                }


                if ($post["2257FormPending"]) {

                    $p_id = $post["2257FormPending"];
//                    db()->query("update photos set active=1 where ".db()->quoteInto("id=?", $p_id));

                    //                   $photos = $this->_data['model']->getAll2257Form();

//                    foreach( $photos as $k=>$ph ){
//                            if($k != $p_id)
//                                unlink(ltrim($ph["file"],'/'));
//                     }
                    // db()->delete("photos", " id_model=".$this->_data["model"]->id." AND type='2257_form' AND id !=".$p_id);
                    $this->_data["model"]->activateDocument("2257_form", $p_id, "pending");

                    if ($p_id != "all") {
//                                    $this->user_notifications->addNotification(
//                                                        $id_from = $_SESSION["user"]["id"],
//                                                        $from_type = $_SESSION["group"],
//                                                        $id_to = $this->_data['model']->id,
//                                                        $to_type = "model",
//                                                        $type="upload_doc",
//                                                        $notification = "Moderator appoved your 2257 document)",
//                                                        $read = 0,
//                                                        $_SERVER["REMOTE_ADDR"],
//                                                        $resource = 0
//                                                    ) ;

                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "approve_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " approved your 2257 document",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $p_id
                        );

                        $this->addNotification($addNotification, "model");

                    } else {
//                            $this->user_notifications->addNotification(
//                                                            $id_from = $_SESSION["user"]["id"],
//                                                            $from_type = $_SESSION["group"],
//                                                            $id_to = $this->_data["model"]->id,
//                                                            $to_type = "model",
//                                                            $type="upload_doc",
//                                                            $notification = "Moderator rejected your 2257 document",
//                                                            $read = 0,
//                                                            $_SERVER["REMOTE_ADDR"],
//                                                            $resource = 0
//                                                        ) ;
                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "reject_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " rejected your 2257 document",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => 0
                        );

                        $this->addNotification($addNotification, "model");
                    }
                    unset($post["2257FormPending"]);
                    unset($p_id);
                }

                if ($post["2257FormRejected"]) {
                    $p_id = $post["2257FormRejected"];

                    $deleted = $this->_data["model"]->getAll2257Form("rejected");

                    if ($this->_data["model"]->deleteDocuments("2257_form", $p_id)) {

                        foreach ($deleted as $k => $del) {
                            if ($k == $p_id || $p_id == "all")
                                unlink(ltrim($del["file"], '/'));
                        }
                    }

                    unset($post["2257FormRejected"]);
                    unset($p_id);
                }

                if ($post["W9FormPending"]) {
                    $p_id = $post["W9FormPending"];
//                    db()->query("update photos set active=1 where ".db()->quoteInto("id=?", $p_id));

//                    $photos = $this->_data['model']->getAll2257Form();

//                    foreach( $photos as $k=>$ph ){
//                            if($k != $p_id)
//                                unlink(ltrim($ph["file"],'/'));
//                     }
                    // db()->delete("photos", " id_model=".$this->_data["model"]->id." AND type='w9_form' AND id !=".$p_id);
                    $this->_data["model"]->activateDocument("w9_form", $p_id, "pending");

                    if ($p_id != "all") {
//                                    $this->user_notifications->addNotification(
//                                                        $id_from = $_SESSION["user"]["id"],
//                                                        $from_type = $_SESSION["group"],
//                                                        $id_to = $this->_data['model']->id,
//                                                        $to_type = "model",
//                                                        $type="upload_doc",
//                                                        $notification = "Moderator appoved your W9 document)",
//                                                        $read = 0,
//                                                        $_SERVER["REMOTE_ADDR"],
//                                                        $resource = 0
//                                                    ) ;
                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "approve_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " approved your W9 document",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $p_id
                        );

                        $this->addNotification($addNotification, "model");

                    } else {
//                            $this->user_notifications->addNotification(
//                                                            $id_from = $_SESSION["user"]["id"],
//                                                            $from_type = $_SESSION["group"],
//                                                            $id_to = $this->_data["model"]->id,
//                                                            $to_type = "model",
//                                                            $type="upload_doc",
//                                                            $notification = "Moderator rejected your W9 document",
//                                                            $read = 0,
//                                                            $_SERVER["REMOTE_ADDR"],
//                                                            $resource = 0
//                                                        ) ;

                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "reject_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " rejected your W9 document",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => 0
                        );

                        $this->addNotification($addNotification, "model");
                    }
                    unset($post["W9FormPending"]);
                    unset($p_id);
                }


                if ($post["W9FormRejected"]) {
                    $p_id = $post["W9FormRejected"];
                    $deleted = $this->_data["model"]->getAllW9Form("rejected");
                    if ($this->_data["model"]->deleteDocuments("w9_form", $p_id)) {

                        foreach ($deleted as $k => $del) {
                            if ($k != $p_id)
                                unlink(ltrim($del["file"], '/'));
                        }
                    }

                    unset($post["W9FormRejected"]);
                    unset($p_id);
                }

                if ($post["releaseFormPending"]) {
                    $p_id = $post["releaseFormPending"];
//                    db()->query("update photos set active=1 where ".db()->quoteInto("id=?", $p_id));

//                    $photos = $this->_data['model']->getAll2257Form();

//                    foreach( $photos as $k=>$ph ){
//                            if($k != $p_id)
//                                unlink(ltrim($ph["file"],'/'));
//                     }
                    // db()->delete("photos", " id_model=".$this->_data["model"]->id." AND type='release_form' AND id !=".$p_id);
                    $this->_data["model"]->activateDocument("release_form", $p_id, "pending");


                    if ($p_id != "all") {
//                                    $this->user_notifications->addNotification(
//                                                        $id_from = $_SESSION["user"]["id"],
//                                                        $from_type = $_SESSION["group"],
//                                                        $id_to = $this->_data['model']->id,
//                                                        $to_type = "model",
//                                                        $type="upload_doc",
//                                                        $notification = "Moderator appoved your release document)",
//                                                        $read = 0,
//                                                        $_SERVER["REMOTE_ADDR"],
//                                                        $resource = 0
//                                                    ) ;

                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "approve_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " approved your release document",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $p_id
                        );

                        $this->addNotification($addNotification, "model");
                    } else {
//                            $this->user_notifications->addNotification(
//                                                            $id_from = $_SESSION["user"]["id"],
//                                                            $from_type = $_SESSION["group"],
//                                                            $id_to = $this->_data["model"]->id,
//                                                            $to_type = "model",
//                                                            $type="upload_doc",
//                                                            $notification = "Moderator rejected your release document",
//                                                            $read = 0,
//                                                            $_SERVER["REMOTE_ADDR"],
//                                                            $resource = 0
//                                                        ) ;
                        $addNotification = array(
                            "id_from" => $_SESSION["user"]["id"],
                            "type_from" => $_SESSION["group"],
                            "id_to" => $this->_data['model']->id,
                            "type_to" => "model",
                            "type" => "reject_doc",
                            "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " rejected your release document",
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => 0
                        );

                        $this->addNotification($addNotification, "model");
                    }
                    unset($post["releaseFormPending"]);
                    unset($p_id);
                }


                if ($post["releaseFormRejected"]) {
                    $p_id = $post["releaseFormRejected"];

                    $deleted = $this->_data["model"]->getAllReleaseForm("rejected");
                    if ($this->_data["model"]->deleteDocuments("release_form", $p_id)) {
                        foreach ($deleted as $k => $del) {
                            if ($k != $p_id)
                                unlink(ltrim($del["file"], '/'));
                        }
                    }

                    unset($post["releaseFormRejected"]);
                    unset($p_id);
                }


                if (isset($_FILES['headshot']) && is_uploaded_file($_FILES['headshot']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/accounts/';

                    $upload = $this->upload->uploadPhoto($photo_dir, 'headshot');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 480, 640);

                        $current_cover = $this->_data['model']->getHeadshot(1);

                        if ($current_cover->id) {
                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("id_model=?", $this->_request->id) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='headshot' ");

                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                } else {
                    //delete headshot
                    if ($post['delete_headshot']) {
                        $current_cover = $this->_data['model']->getHeadshot(1);
                        if ($current_cover->id) {
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }
                    }
                }

                if (isset($_FILES['photo_id']) && is_uploaded_file($_FILES['photo_id']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/accounts/';
                    $upload = $this->upload->uploadPhoto($photo_dir, 'photo_id');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 640, 480);

                        $current_cover = $this->_data['model']->getPhotoId(1);

                        if ($current_cover->id) {
                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("id_model=?", $this->_request->id) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='photo_id' ");

                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                } else {
                    //delete photo_id
                    if ($post['delete_photo_id']) {
                        $current_cover = $this->_data['model']->getPhotoId(1);
                        if ($current_cover->id) {
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }
                    }
                }

                if (isset($_FILES['2257_form']) && is_uploaded_file($_FILES['2257_form']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/accounts/';

                    $upload = $this->upload->uploadPhoto($photo_dir, '2257_form');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 800);

                        $current_cover = $this->_data['model']->get2257Form(1);

                        if ($current_cover->id) {
                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("id_model=?", $this->_request->id) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='2257_form' ");


                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }

                if (isset($_FILES['w9_form']) && is_uploaded_file($_FILES['w9_form']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/accounts/';

                    $upload = $this->upload->uploadPhoto($photo_dir, 'w9_form');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 800);

                        $current_cover = $this->_data['model']->getW9Form(1);

                        if ($current_cover->id) {
                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("id_model=?", $this->_request->id) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='w9_form' ");


                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }

                if (isset($_FILES['release_form']) && is_uploaded_file($_FILES['release_form']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/accounts/';

                    $upload = $this->upload->uploadPhoto($photo_dir, 'release_form');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 800);

                        $current_cover = $this->_data['model']->getReleaseForm(1);

                        if ($current_cover->id) {
                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/accounts/' . $current_cover->filename);
                        }

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("id_model=?", $this->_request->id) . ", " . db()->quoteInto("filename=?", $filename) . ",active=1,type='release_form' ");


                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }

                if ($post['same_address']) $post['address'] = $post['address_real'];
                unset($post['same_address']);

                $post['birthday_real'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
                unset($post['birthday_year']);
                unset($post['birthday_month']);
                unset($post['birthday_day']);
                unset($post['save']);
                unset($post['delete_headshot']);
                unset($post['delete_photo_id']);
                unset($post['headshot']);
                unset($post['photo_id']);
                unset($post['2257_form']);
                unset($post['w9_form']);
                unset($post['release_form']);

                unset($post['country_name']);
                unset($post['region_name']);
                unset($post['city_name']);
                unset($post['gift_country_name']);
                unset($post['gift_region_name']);
                unset($post['gift_city_name']);

                unset($post['confirm_password']);

                //update/add model_moderators
                $this->model_moderator->delete("id_model = " . $this->_data["model"]->id . " AND id_moderator = " . (int)$post["assigned_to"]);
                $this->model_moderator->insert(array("id_model" => $this->_data["model"]->id, "id_moderator" => (int)$post["assigned_to"]));

                //               $this->user_notifications->addNotification(
//                    $id_from = $_SESSION["user"]["id"],
//                    $from_type = $_SESSION["group"],
//                    $id_to = $this->_data['model']->id,
//                    $to_type = "model",
//                    $type="moderator_assigned",
//                    $notification = "Moderator has been assigned to you",
//                    $read = 0,
//                    $_SERVER["REMOTE_ADDR"],
//                    $resource = (int)$post["assigned_to"]
//                ) ;


                $addNotification = array(
                    "id_from" => $_SESSION["user"]["id"],
                    "type_from" => $_SESSION["group"],
                    "id_to" => $this->_data['model']->id,
                    "type_to" => "model",
                    "type" => "moderator_assign",
                    "notification" => "Moderator has been asigned to you",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "resource" => (int)$post["assigned_to"]
                );

                $this->addNotification($addNotification, "model");
                //  $this->user_notifications->addNotification(
//                    $id_from = $_SESSION["user"]["id"],
//                    $from_type = $_SESSION["group"],
//                    $id_to = (int)$post["assigned_to"],
//                    $to_type = "moderator",
//                    $type="moderator_assigned",
//                    $notification = "You have been assigned as moderator for  " . $this->_data["model"]->screen_name,
//                    $read = 0,
//                    $_SERVER["REMOTE_ADDR"],
//                    $resource = $this->_data["model"]->id
//                ) ;


                $addNotification = array(
                    "id_from" => $_SESSION["user"]["id"],
                    "type_from" => $_SESSION["group"],
                    "id_to" => (int)$post["assigned_to"],
                    "type_to" => "moderator",
                    "type" => "moderator_assign",
                    "notification" => "You have been assigned as moderator for  " . $this->_data["model"]->screen_name,
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "resource" => $this->_data["model"]->id
                );

                $this->addNotification($addNotification, "moderator");

                unset($post["assigned_to"]);


                $this->model->update($post, $this->model->getAdapter()->quoteInto("id=?", $this->_request->id));

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-account-manage", (user()->id == 0 ? "admin - " . $notice_msq . $file_upload_failed : "moderator - " . $notice_msq . $file_upload_failed), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq . $file_upload_failed));
            }

            if ($this->_request->save2 && !$this->acl->isAllowed($_SESSION['group'], "model-permissions", "edit")) return $this->_forward("restrict");

            if ($this->_request->save2 == 'Save permissions') { // model permissions
                $notice_msq = $this->_request->name . "'s permissions have been successfully saved!";
                unset($post['save2']);

                $actions = user_permissions();
                $actions = $actions['model'];

                foreach ($actions as $action) {
                    $perm = '';

                    if ($post[$action . "_view"]) $perm .= "view";
                    if ($post[$action . "_edit"]) {
                        if ($perm == '') $perm .= "edit";
                        else $perm .= ",edit";
                    };
                    if ($perm == '') $this->permissions->deletePermission($this->_request->id, 'model', $action);
                    else {
                        $this->permissions->setPermission($this->_request->id, 'model', $action, $perm);

                    }

                }

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-permissions-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-model-account"));
        }

    }

    public function modelratesAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "model-rates", "view")) return $this->_forward("restrict");


        $this->load('model');
        $this->load('model_rates');
        $this->load('model_rates_pending');
        $this->load('rates');
        $this->load("user_settings");
        $this->load('rates_limits');


        $this->_data['rates_fields_pending'] = $this->model_rates_pending->getRatesByModel($this->_request->id);
        $this->_data['rates_fields'] = $this->model_rates->getRatesByModel($this->_request->id);
        $this->_data['model'] = $this->model->find((int)$this->_request->id)->current();

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if ($this->_request->save3 == 'Reset') { // Request new rates
                unset($post['save3']);
                $notice_msq = $this->_request->name . "'s rate limit has been reset!";

                $result = db()->query("update model_rates set " . db()->quoteInto("special=?", 0) . " where " . db()->quoteInto("id_model=?", $this->_request->id) . " and " . db()->quoteInto("id_rate=?", $post['id_rate']));

                if (!$result) $notice_msq = "Opperation failed! Please try again.";
                else {
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-rates-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());
                }
                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            if ($this->_request->raise_limits == 'Save rates') { // Request new rates
                unset($post['raise_limits']);
                $limits_breach = false;
                foreach ($post as $key => $val) {

                    $_rates = $this->model_rates->getModelRateById($this->_request->id, $key);

                    //check limits
                    $rate_min = $this->rates_limits->getLimit($key, 'min');
                    $rate_max = $this->rates_limits->getLimit($key, 'max');

                    if ($val >= $rate_min->value && $val <= $rate_max->value) {
                        if ($_rates->id_rate) { //request pending for rate


                            if ($_rates->value != $val) { //update with new value if != model_rates
                                db()->query("update model_rates set " . db()->quoteInto("value=?", $val) . " where " . db()->quoteInto("id_model=?", $this->_request->id) . " and " . db()->quoteInto("id_rate=?", $key));
                            }


                        } else { //no request sent yet

                            db()->query("insert into model_rates set " . db()->quoteInto("value=?", $val) . ", " . db()->quoteInto("id_model=?", $this->_request->id) . "," . db()->quoteInto("id_rate=?", $key));


                        }
                    } else {
                        $limits_breach = true;

                    }


                }
                if ($limits_breach) $this->_helper->FlashMessenger->addMessage(notice("Limits not respected! Rates not saved!"));
                else {
                    $notice_msq = $this->_request->name . "'s rates saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-rates-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());
                }
            }

            if ($this->_request->save2 == 'Save special rates') { // model permissions
                $notice_msq = $this->_request->name . "'s requests approved/denied!";
                unset($post['save2']);

                //save / delete rates requests
                foreach ($this->_data['rates_fields_pending'] as $rate) {
                    if (isset($post[$rate['id']])) {
                        if ($post[$rate['id']] == 1) { //save rate - model_rates->special ; send mail

                            //save rate
                            db()->query("update model_rates set " . db()->quoteInto("special=?", $rate['value']) . " where " . db()->quoteInto("id_model=?", $this->_request->id) . " and " . db()->quoteInto("id_rate=?", $rate['id']));
                            //delete from pending
                            db()->query("delete from model_rates_pending where " . db()->quoteInto("id_model=?", $this->_request->id) . " and " . db()->quoteInto("id_rate=?", $rate['id']));

                            //TO DO - send mail

                        } else { //delete rate ; send mail
                            //delete from pending
                            db()->query("delete from model_rates_pending where " . db()->quoteInto("id_model=?", $this->_request->id) . " and " . db()->quoteInto("id_rate=?", $rate['id']));

                            //TO DO - send mail
                        }
                    }

                }

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "model", "model-rates-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-model-rates"));
        }


    }

    public function useraccountsettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "user-account-settings", "view")) return $this->_forward("restrict");

        $this->load('countries');
        $this->load('user');

        $this->_data['countries'] = $this->countries->fetchAllLocations('co');

        $this->_data['user'] = $this->user->find((int)$this->_request->id)->current();

        if ($this->request->isPost()) {

            if (!$this->acl->isAllowed($_SESSION['group'], "user-account-settings", "edit")) return $this->_forward("restrict");

            $post = $this->_request->getPost();

            if ($this->_request->save == 'Save') { // user account info
                $notice_msq = $this->_request->name . "'s account settings have been successfully saved!";
                unset($post['save']);

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "user", "user-account-manage", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->user->update($post, $this->user->getAdapter()->quoteInto("id=?", $this->_request->id));
                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-user-account"));
        }
    }

    public function moderatoraccountsettingsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "moderator-account-settings", "view")) return $this->_forward("restrict");

        $this->load('countries');
        $this->load('moderator');
        $this->load('permissions');
        $this->_data['countries'] = $this->countries->fetchAllLocations('co');

        $this->_data['moderator'] = $this->moderator->find((int)$this->_request->id)->current();

        $perms = $this->permissions->getPermissionsForUser($this->_request->id, 'moderator');
        if ($perms) $perms = $perms->toArray();
        else $perms = array();
        $permissions = array();
        if (count($perms) > 0) {
            foreach ($perms as $perm) {
                if ($perm['action'] == 'all' && $perm['type']) { //for superadmin
                    $actions = user_permissions();
                    $actions = $actions['moderator'];
                    foreach ($actions as $action) {
                        $types = explode(",", $perm['permission']);
                        foreach ($types as $type) {
                            $permissions[$action . "_" . $type] = true;
                        }

                    }
                } else {
                    $types = explode(",", $perm['permission']);
                    foreach ($types as $type) {
                        $permissions[$perm['action'] . "_" . $type] = true;
                    }

                }
            }
        }
        $this->_data['permissions'] = $permissions;

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();


            if ($this->_request->save && !$this->acl->isAllowed($_SESSION['group'], "moderator-account-settings", "edit")) return $this->_forward("restrict");

            if ($this->_request->save == 'Save') { // moderator account info
                $notice_msq = $this->_request->name . "'s account settings have been successfully saved!";
                unset($post['save']);

                $this->moderator->update($post, $this->moderator->getAdapter()->quoteInto("id=?", $this->_request->id));

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "moderator", "moderator-account-manage", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            if ($this->_request->save2 && !$this->acl->isAllowed($_SESSION['group'], "moderator-permissions", "edit")) return $this->_forward("restrict");

            if ($this->_request->save2 == 'Save permissions') { // moderator permissions
                $notice_msq = $this->_request->name . "'s permissions have been successfully saved!";
                unset($post['save2']);
                $actions = user_permissions();
                $actions = $actions['moderator'];

                foreach ($actions as $action) {
                    $perm = '';
                    if ($post[$action . "_view"]) $perm .= "view";
                    if ($post[$action . "_edit"]) {
                        if ($perm == '') $perm .= "edit";
                        else $perm .= ",edit";
                    };

                    if ($perm == '') $this->permissions->deletePermission($this->_request->id, 'moderator', $action);
                    else $this->permissions->setPermission($this->_request->id, 'moderator', $action, $perm);

                }

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", (int)$this->_request->id, "moderator", "moderator-permissions", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            $this->_redirect($this->view->url(array("id" => $this->_request->id, "name" => $this->_request->name, "manage" => "edit"), "manage-moderator-account"));
        }
    }

    public function restrictAction()
    {

    }

    public function managechipsAction()
    {

        $this->load('chips');
        $this->_data['payments'] = App_Payment_Registry::getInstance();



        switch ($this->_request->user_type) {
            case 'model':

                if (!$this->acl->isAllowed($_SESSION['group'], "manage-model-chips", "edit")) return $this->_forward("restrict");

                $this->load('model');

                $id_model = $this->params["id"];
                $this->_data['model'] = $this->model->getModelById($id_model);
                if (!$this->_data['model']->id) $this->_redirect("/404");
                $this->_data['page_title'] = 'Manage chips for model ' . $this->_data['model']->screen_name;

                $this->load("earning_stats");
                $this->_data["earnings"] = $this->earning_stats->fetchModelStats($this->_data["model"]->id);

                $this->load("payments_info");
                $methods = $this->payments_info->getPaymentsMethodsByUserId($this->_data['model']->id, 'model');

                $payments = array();
                if ($methods) {
                    foreach ($methods as $method) {
                        $decoded = unserialize($method->info);
                        foreach ($decoded as $k => $v) {
                            $payments[$method->name] = $v;
                            break;
                        }
                    }
                }

                $this->_data["payments"] = $payments;

                if ($this->request->isPost()) {
                    if ($this->_request->saveChips) {

                        $post = $this->_request->getPost();

                        if ($post['chips'] < 0) {
                            $this->_helper->FlashMessenger->addMessage(notice('Chip amnount can not be below 0!'));
                            $this->_redirect($this->view->url(array("user_type" => 'model', "id" => $this->params['id'], "name" => $this->params['name']), "manage-chips"));
                        }

                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "moderator", $this->params['id'], "model", "manage-model-chips", "Model chips changed from " . $this->_data['model']->chips . " to " . $post['chips'], 1, getUserIp());

                        $this->model->update(array("chips" => $post['chips']), "id=" . $this->_data['model']->id);

                        $this->_helper->FlashMessenger->addMessage(notice('Chip amnount changed from ' . $this->_data['model']->chips . ' to ' . $post['chips']));
                        $this->_redirect($this->view->url(array("user_type" => 'model', "id" => $this->params['id'], "name" => $this->params['name']), "manage-chips"));

                    } else if ($this->_request->saveRepShare) {
                        $post = $this->_request->getPost();

                        if ($post['repShare'] < 0) {
                            $this->_helper->FlashMessenger->addMessage(notice('RepShare can not be below 0!'));
                            $this->_redirect($this->view->url(array("user_type" => 'model', "id" => $this->params['id'], "name" => $this->params['name']), "manage-chips"));
                        }

                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "moderator", $this->params['id'], "model", "manage-model-chips", "Model Rep Share changed from " . $this->_data['model']->rep_share . " to " . $post['repShare'], 1, getUserIp());

                        $this->model->update(array("rep_share" => $post['repShare']), "id=" . $this->_data['model']->id);

                        $this->_helper->FlashMessenger->addMessage(notice('RepShare changed from ' . $this->_data['model']->rep_share . ' to ' . $post['repShare']));
                        $this->_redirect($this->view->url(array("user_type" => 'model', "id" => $this->params['id'], "name" => $this->params['name']), "manage-chips"));


                    }
                }

                break;

            case 'user':
                if (!$this->acl->isAllowed($_SESSION['group'], "manage-user-chips", "edit")) return $this->_forward("restrict");

                $this->load('user');

                $this->_data['users'] = $this->_data['user'] = $this->user->find((int)$this->_request->id)->current();
                $this->_data['page_title'] = 'Manage chips for user ' . $this->_data['user']->username;

                if ($this->request->isPost()) {
                    $post = $this->_request->getPost();

                    if ($post['chips'] < 0) {
                        $this->_helper->FlashMessenger->addMessage(notice('Chip amnount can not be below 0!'));
                        $this->_redirect($this->view->url(array("user_type" => 'user', "id" => $this->params['id'], "name" => $this->params['name']), "manage-chips"));

                    }

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", $this->params['id'], "user", "manage-user-chips", "User chips changed from " . $this->_data['user']->chips . " to " . $post['chips'], 1, getUserIp());


//                    echo "<pre>";
//                    print_r($this->_data['user']);
//                    echo "</pre>";
//                    exit;

                    $this->user->update(array("chips" => $post['chips']), "id=" . $this->_data['user']->id);


                    $this->_helper->FlashMessenger->addMessage(notice('Chip amnount changed from ' . $this->_data['user']->chips . ' to ' . $post['chips']));
                    $this->_redirect($this->view->url(array("user_type" => 'user', "id" => $this->params['id'], "name" => $this->params['name']), "manage-chips"));
                }

                break;

            default:
                return $this->_forward("restrict");
                break;

        }
    }

    public function managenotesAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], $this->_request->type . "-notes", "view")) return $this->_forward("restrict");

        switch ($this->_request->type) {
            case 'model':
                $this->load('model');
                $this->load('moderator_notes');
                if ($this->_request->id != 'all') {
                    $this->_data['models'] = $this->_data['model'] = $this->model->find((int)$this->_request->id)->current();
                    $this->_data['notes'][$this->_data['models']->id] = $this->moderator_notes->getNotesByModerator(user()->id, $this->_data['models']->id, $this->_request->type);
                } else {
                    $this->_data['models'] = $this->model->fetchAll($this->model->select()->where("active=1"));
                    foreach ($this->_data['models'] as $model) {
                        $this->_data['notes'][$model->id] = $this->moderator_notes->getNotesByModerator(user()->id, $model->id, $this->_request->type);
                    }

                }
                break;
            case 'moderator':
                $this->load('moderator');
                $this->load('moderator_notes');
                if ($this->_request->id != 'all') {
                    $this->_data['moderators'] = $this->_data['moderator'] = $this->moderator->find((int)$this->_request->id)->current();
                    $this->_data['notes'][$this->_data['moderators']->id] = $this->moderator_notes->getNotesByModerator(user()->id, $this->_data['moderators']->id, $this->_request->type);
                } else {
                    $this->_data['moderators'] = $this->moderator->fetchAll($this->moderator->select()->where("active=1"));
                    foreach ($this->_data['moderators'] as $model) {
                        $this->_data['notes'][$model->id] = $this->moderator_notes->getNotesByModerator(user()->id, $model->id, $this->_request->type);
                    }
                }
                break;
            case 'user':
                $this->load('user');
                $this->load('moderator_notes');
                if ($this->_request->id != 'all') {
                    $this->_data['users'] = $this->_data['user'] = $this->user->find((int)$this->_request->id)->current();
                    $this->_data['notes'][$this->_data['users']->id] = $this->moderator_notes->getNotesByModerator(user()->id, $this->_data['users']->id, $this->_request->type);
                } else {
                    $this->_data['users'] = $this->user->fetchAll($this->user->select()->where("state=1"));
                    foreach ($this->_data['users'] as $model) {
                        $this->_data['notes'][$model->id] = $this->moderator_notes->getNotesByModerator(user()->id, $model->id, $this->_request->type);
                    }
                }
                break;
        }

        if ($this->request->isPost()) {

            if (!$this->acl->isAllowed($_SESSION['group'], $this->_request->type . "-notes", "edit")) return $this->_forward("restrict");

            $post = $this->_request->getPost();

            if ($this->_request->save == 'Save notes') { // user account info
                $notice_msq = "The notes" . ($this->_request->id == 'all' ? "" : " for " . $this->_request->name) . " have been successfully saved!";
                unset($post['save']);

                switch ($this->params['type']) {
                    case 'model':
                        if (count($this->_data['models']) > 1) {
                            $users = $this->_data['models']->toArray();
                        } else $users = array($this->_data['models']->toArray());
                        break;
                    case 'moderator':
                        if (count($this->_data['moderators']) > 1) {
                            $users = $this->_data['moderators']->toArray();
                        } else $users = array($this->_data['moderators']->toArray());
                        break;
                    case 'user':
                        if (count($this->_data['users']) > 1) {
                            $users = $this->_data['users']->toArray();
                        } else $users = array($this->_data['users']->toArray());
                        break;
                }
                foreach ($users as $user) {

                    $result = $this->moderator_notes->setNotes(user()->id, $user['id'], $this->_request->type, $post[$user['id']]);
                    if (!$result) $notice_msq = "Opperation failed! Please try again.";
                }

                if ($notice_msq != "Opperation failed! Please try again.") {
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "manage-" . $this->params['type'] . "-notes", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());
                }

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
            }

            $this->_redirect($this->view->url(array("type" => $this->_request->type, "id" => $this->_request->id, "name" => $this->_request->name, "manage" => $this->_request->manage), "manage-notes"));
        }
    }

    public function announcementsAction()
    {

        $this->load('announcements');

        switch ($this->_request->type) {
            case 'edit':

                if (!$this->acl->isAllowed($_SESSION['group'], "add-announcements", "edit")) return $this->_forward("restrict");

                if (!$this->_request->id) $this->_redirect("/404/");

                $this->_data['ann'] = $this->announcements->getAnnouncementById($this->_request->id);

                if (!$this->_data['ann']->id) $this->_redirect("/404/");

                $post = $this->_request->getPost();

                if ($post['save'] == 'Save' || $post['save_draft'] == 'Save as draft') {
                    unset($post['save']);
                    if ($post["save_draft"]) {
                        unset($post['save_draft']);
                        $post["active"] = -1;
                    }
                    $post['go_live'] = strtotime($post['go_live']);

                    $this->announcements->update($post, $this->announcements->getAdapter()->quoteInto("id=?", $this->_data['ann']->id));
                    $notice_msq = "Announcement has been successfully saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->_redirect($this->view->url(array("type" => "edit", "id" => $this->_data['ann']->id), "mod-announcements-edit"));
                }

                $this->_data['page_title'] = 'Edit announcement';
                $this->_data['type'] = 'edit';
                break;

            case 'add':

//                if (!$this->acl->isAllowed($_SESSION['group'], "add-announcements", "edit")) return $this->_forward("restrict");

                $post = $this->_request->getPost();

                if ($post['save'] == 'Save' || $post['save_draft'] == 'Save as draft') {

                    unset($post['save']);
                    $post["active"] = 1;
                    if ($post["save_draft"]) {
                        unset($post['save_draft']);
                    }

                    //$post['go_live'] = $post['go_live'] = 'now' ? time() : strtotime($post['go_live']);

                    if ($post['text'] && $post['section']) {
                        $post['id_moderator'] = $_SESSION['user']['id'];
                        $this->announcements->insert($post);
                        $this->_helper->FlashMessenger->addMessage(notice("Announcement has been successfully saved!"));
                        $this->_redirect($this->view->url([], "mod-announcements"));
                    }
                    else {
                        $this->_helper->FlashMessenger->addMessage(notice("Please complete all fields!"));
                        $this->_redirect($this->view->url([], "mod-announcements"));
                    }
                }

                $this->_data['page_title'] = 'Create announcement';
                $this->_data['type'] = 'add';
                break;

            case 'delete':

                if (!$this->acl->isAllowed($_SESSION['group'], "add-announcements", "edit")) return $this->_forward("restrict");

                $this->announcements->delete("id=" . $this->_request->id);
                $notice_msq = "Announcement has been successfully deleted!";
                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));


                $this->_redirect($this->view->url([], "mod-announcements"));

                break;

            default:

                $post = $this->params;

                /*if ($this->acl->isAllowed($_SESSION['group'], "add-announcements", "edit")) {
                    $section = '';
                    $live = '';
                } else {*/


                 if ($post["group_type"]) {
                    $section = $post["group_type"];
                 }
                else {
                    $section = Auth::getUser()->getRole();
                }
                $section = strtoupper($section);
                    $live = '1';
                //}

                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $sort = $post["sort"];

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                if (isset($post['start'])) $start = ($post["start"]); else  $start = null;
                if (isset($post['end'])) $end = ($post["end"]); else  $end = null;

                $paginator = Zend_Paginator::factory($this->announcements->getAnnouncements($section, $live, $start, $end, $sort));
                $paginator->setItemCountPerPage("250000");
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                //var_dump($this->announcements->getAnnouncements($section, $live, $start, $end, $sort));

                $this->_data['page_title'] = 'Announcement';
                $this->_data['type'] = 'all';
                $this->_data['paginator'] = $paginator;
                $this->_data['test'] = 'test 123';
                break;
        }
    }

    public function templatesAction()
    {

        $this->load('templates');

        if (!$this->acl->isAllowed($_SESSION['group'], "email-templates", "view")) return $this->_forward("restrict");

        $this->_data['templates'] = $this->templates->fetchAll();

        if ($this->_request->name) $this->_data['template'] = $this->templates->getContent($this->_request->name);

        $post = $this->_request->getPost();
        if ($post['save'] == 'Save') {

            if (!$this->acl->isAllowed($_SESSION['group'], "email-templates", "edit")) return $this->_forward("restrict");
            unset($post['save']);

            //save page content
            $this->templates->update($post, $this->templates->getAdapter()->quoteInto("name=?", $post['name']));
            $notice_msq = $post['title'] . " template has been successfully saved!";
            $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

            $this->load("notifications");
            $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "email-templates-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

            $this->_redirect($this->view->url(array('name' => $post['name']), "email-templates1"));
        }

    }

    public function pagesAction()
    {

        $this->load('static_pages');
        $this->load('model');

        //if (!$this->acl->isAllowed($_SESSION['group'], "static-pages", "view")) return $this->_forward("restrict");

        $this->_data['pages'] = $this->static_pages->getPages();
        if ($this->_request->page) $this->_data['page'] = $this->static_pages->getContent($this->_request->page);

        $post = $this->_request->getPost();
        if ($post['save'] == 'Save') {

            if (!$this->acl->isAllowed($_SESSION['group'], "static-pages", "edit")) return $this->_forward("restrict");
            unset($post['save']);

            if ($this->static_pages->getContent($this->_request->page)->content != $post['content'] && $this->static_pages->getContent($this->_request->page)->page == "model_release_form") {
                $post['added'] = time();
                //p($this->model->getModels());
                $this->model->update(array("terms_agreed" => 2));

            }
            //save page content
            $this->static_pages->update($post, $this->static_pages->getAdapter()->quoteInto("page=?", $post['page']));
            //p($this->_request->page);

            $notice_msq = $post['title'] . " page has been successfully saved!";
            $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

            $this->load("notifications");
            $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "static-pages-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

            $this->_redirect($this->view->url(array('page' => $post['page']), "static-pages-edit1"));
        }

    }

    public function developmentAction()
    {

        $this->load('static_pages');

        /*if (!$this->acl->isAllowed($_SESSION['group'], "development-pages", "view")) return $this->_forward("restrict");*/

        $parentPagesTop = $this->static_pages->getPages('backend');

        $this->_data['pages'][] = array("page" => null, "title" => 'None', 'children' => array());

        foreach ($parentPagesTop as $pageTop) {
            $childrenTop = array();
            $parentPagesLvl1 = $this->static_pages->getPages('backend', $pageTop->page);
            foreach ($parentPagesLvl1 as $pageLvl1) {
                $childrenLvl1 = array();
                $parentPagesLvl2 = $this->static_pages->getPages('backend', $pageLvl1->page);
                foreach ($parentPagesLvl2 as $pageLvl2) {
                    $childrenLvl1[] = array("page" => $pageLvl2->page, "title" => $pageLvl2->title);
                }
                $childrenTop[] = array("page" => $pageLvl1->page, "title" => $pageLvl1->title, 'children' => $childrenLvl1);
            }
            $this->_data['pages'][] = array("page" => $pageTop->page, "title" => $pageTop->title, 'children' => $childrenTop);
        }

        if ($this->_request->page) $this->_data['page'] = $this->static_pages->getContent($this->_request->page, 'backend');

        if ($this->_request->manage == 'delete' && $this->_request->page) {

            //delete development page
            $this->static_pages->delete($this->static_pages->getAdapter()->quoteInto("page=?", $this->_request->page) . " and " . $this->static_pages->getAdapter()->quoteInto("type=?", "backend"));
            $notice_msq = "The page has been successfully deleted!";
            $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

            $this->load("notifications");
            $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "development-pages-delete", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

            $this->_redirect($this->view->url(array(), "development-pages-edit"));


        } else {

            //save or add development page
            $post = $this->_request->getPost();
            if ($post['save'] == 'Save') {

                if (!$this->acl->isAllowed($_SESSION['group'], "development-pages", "edit")) return $this->_forward("restrict");
                unset($post['save']);
                $post['type'] = 'backend';
                if ($post['parent'] == 'null') $post['parent'] = null;

                //save page content
                $this->static_pages->update($post, $this->static_pages->getAdapter()->quoteInto("page=?", $post['page']));
                $notice_msq = $post['title'] . " page has been successfully saved!";
                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "development-pages-edit", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->_redirect($this->view->url(array('page' => $post['page']), "development-pages-edit1"));
            }
            if ($post['save_new'] == 'Save') {

                if (!$this->acl->isAllowed($_SESSION['group'], "development-pages", "edit")) return $this->_forward("restrict");
                unset($post['save_new']);
                $post['type'] = 'backend';
                $post['status'] = '1';
                $post['page'] = trim(strtolower(striptext($post['title'])));
                $post['page'] = preg_replace('/([ \n\r\t])+ /i', '-', $post['page']);
                $post['page'] = preg_replace('/(-)+/i', '-', $post['page']);
                if ($post['parent'] == 'null') $post['parent'] = null;
                //save page content
                $this->static_pages->insert($post);
                $notice_msq = $post['title'] . " page has been successfully added!";
                $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "development-pages-add", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                $this->_redirect($this->view->url(array('page' => $post['page']), "development-pages-edit1"));
            }
        }
    }

    public function rulesAction()
    {

        $params = $this->_request->getParams();
        /*if (!$this->acl->isAllowed($_SESSION['group'], "rules", "view") || !$params['type']) return $this->_forward("restrict");*/

        $this->load('rules');
        $this->_data['rules'] = $this->rules->getRules($params['type'] ? $params['type'] : ($this->user &&
        !$this->user->hasAdminRole() ? $this->user->getRole() : Role::USER));

    }

    public function revenuestatsAction()
    {

    }

    public function systemsettingsAction()
    {

        $this->load('timezones');
        $this->_data['timezones'] = $this->timezones->fetchAll();

        $this->load("countries");
        $this->_data["gift_country"] = $this->countries->getLocationById(config()->gift_country);

        $this->_data["gift_city"] = $this->countries->getLocationById(config()->gift_city);
        $this->_data["gift_region"] = $this->countries->getLocationById(config()->gift_region);

        if ($this->request->isPost()) {

            $post = $this->_request->getPost();


            if ($this->request->isPost()) {

                $post = $this->_request->getPost();

                if ($this->_request->save && !$this->acl->isAllowed($_SESSION['group'], "system-settings", "edit")) return $this->_forward("restrict");
                //salvare time zone
                if ($this->_request->save == 'Save timezone') { //timezone

                    if (!$this->acl->isAllowed($_SESSION['group'], "mod-timezone", "edit")) return $this->_forward("restrict");

                    $gmt = str_replace('.0', '', $this->timezones->getGMT($post['timezone']));

                    ini_set('date.timezone', 'Etc/GMT' . $gmt);

                    $this->load('config');
                    $this->config->update(array("val" => $post["timezone"]), "var='timezone'");
                    $config = config();
                    $config->timezone = $post["timezone"];
                    reg()->set('config', $config);

                    $notice_msq = "Timezone has been successfully saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "system-settings", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                    $this->_redirect($this->view->url(array(), "system-settings"));
                }

                // max_time_allowed - maxim time guest user allowed on chatt before forced logout
                if ($this->_request->save == 'Save guest time') {
                    if (!$this->acl->isAllowed($_SESSION['group'], "max_allowed_time", "edit")) return $this->_forward("restrict");

                    $this->load('config');
                    $this->config->update(array("val" => $post["max_allowed_time"]), "var='max_allowed_time'");
                    $config = config();
                    $config->max_allowed_time = $post["max_allowed_time"];
                    reg()->set('config', $config);

                    $notice_msq = "Maximum time allowed for guests has been successfully saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "system_settings", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                    $this->_redirect($this->view->url(array(), "system-settings"));
                }
                // max_allowed_time_without_chips - maxim time  user without chips allowed on chatt before forced logout
                if ($this->_request->save == 'Save user time') {
                    if (!$this->acl->isAllowed($_SESSION['group'], "max_allowed_time", "edit")) return $this->_forward("restrict");

                    $this->load('config');
                    $this->config->update(array("val" => $post["max_allowed_time_without_chips"]), "var='max_allowed_time_without_chips'");
                    $config = config();
                    $config->max_allowed_time_without_chips = $post["max_allowed_time_without_chips"];
                    reg()->set('config', $config);

                    $notice_msq = "Maximum time allowed for unsigned users without chips has been successfully saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "system_settings", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                    $this->_redirect($this->view->url(array(), "system-settings"));
                }

                //salvare group user number
                if ($this->_request->save == 'Save user number') {

                    if (!$this->acl->isAllowed($_SESSION['group'], "max_group_users", "edit")) return $this->_forward("restrict");


                    $this->load('config');
                    $this->config->update(array("val" => $post["max_group_users"]), "var='max_group_users'");
                    $config = config();
                    $config->max_group_users = $post["max_group_users"];
                    reg()->set('config', $config);

                    $notice_msq = "Users group number saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "system-settings", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                    $this->_redirect($this->view->url(array(), "system-settings"));
                }

                // defaul gift address
                if ($this->_request->save == 'Save gift address') {
                    if (!$this->acl->isAllowed($_SESSION['group'], "gift_office_address", "edit")) return $this->_forward("restrict");


                    $this->load('config');
                    $this->config->update(array("val" => $post["gift_address"]), "var='gift_address'");
                    $this->config->update(array("val" => $post["gift_country"]), "var='gift_country'");
                    $this->config->update(array("val" => $post["gift_region"]), "var='gift_region'");
                    $this->config->update(array("val" => $post["gift_city"]), "var='gift_city'");
                    $this->config->update(array("val" => $post["gift_zip"]), "var='gift_zip'");
                    $config = config();
                    $config->gift_address = $post["gift_address"];
                    $config->gift_country = $post["gift_country"];
                    $config->gift_region = $post["gift_region"];
                    $config->gift_zip = $post["gift_zip"];
                    reg()->set('config', $config);

                    $notice_msq = "Gift address successfully saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "moderator", user()->id, "moderator", "system_settings", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                    $this->_redirect($this->view->url(array(), "system-settings"));
                }
            }
        }
    }

    public function notificationsAction()
    {

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
                $this->_redirect($this->view->url(array(), "moderator-notifications"));
            } elseif ($post["mark_delete"]) {
                $this->user_notifications->deleteNotifications($post["multiple_select"]);
            }
        }

        $notifications = $this->user_notifications->getAllType($_SESSION["group"], $_SESSION["user"]["id"], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));
        foreach ($notifications as $n) {
            $last_notification = $n->id;
            break;
        }
        if ($last_notification >= $_SESSION["user"]["last_notification"]) {
            //update session
            $_SESSION["user"]["last_notification"] = $last_notification;
            $this->load("moderator");
            $this->moderator->update(array("last_notification" => $last_notification), "id=" . $_SESSION["user"]["id"]);
        }

        $paginator = Zend_Paginator::factory($notifications);
        $paginator->setItemCountPerPage("25");
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        //$this->_data["notifications"] = $notifications;
    }

    public function messagesAction()
    {

        if (!Auth::isModerator()) $this->_redirect("/404/");

        $this->load('messages');
        $this->load('model');
        $this->load('user');
        $this->load('moderator');

        $this->_data['message_action'] = $this->_request->message_action;

        $post = $this->params;

        if ($this->_request->isPost() && $this->_request->message_action != "compose") {

            //           if(isset($post["delete"]))
//                $this->messages->deleteMessages($post["multiple_select"]);
            if (isset($post["archive"]))
                $this->messages->archiveMessages($post["multiple_select"]);
            if (isset($post["delete"]))
                $this->messages->deleteMessages($post["multiple_select"]);
            if (isset($post["read"]))
                $this->messages->updateMessages($post["multiple_select"], "1");
            if (isset($post["unread"]))
                $this->messages->updateMessages($post["multiple_select"], "0");
            /* refresh count */
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
        }

        switch ($this->_request->message_action) {

            case "inbox":

                $this->_data['page_title'] = 'Inbox';

                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);
                unset($post['delete']);
                unset($post['read']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }
                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();

                $this->_data["users"] = $users;

                $paginator = Zend_Paginator::factory($this->messages->getModeratorInbox($_SESSION['user']['id']));
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
                unset($post['delete']);
                unset($post['read']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }
                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();

                $count = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
                $this->_data['unread_count'] = $count;

                $this->_data["users"] = $users;

                $paginator = Zend_Paginator::factory($this->messages->getModeratorArchive($_SESSION['user']['id']));
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


                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();

                $count = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
                $this->_data['unread_count'] = $count;

                $this->_data["users"] = $users;

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->messages->getModeratorOutbox($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "compose":

                $this->_data['page_title'] = 'compose message';

                $count = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
                $this->_data['unread_count'] = $count;

                if ($this->_request->isPost()) {

                    $post = $this->_request->getPost();
                    //p($post,1);
                    if ($post['send'] == 'Send Message' && $post['id_receiver'] >= 0 && $post['receiver_type'] && $post['subject'] && $post['message']) {
                        //$the_user = $this->user->getUserByName($post['sendtouser']);
                        //if(!$the_user->id) {
                        //    $this->_helper->FlashMessenger->addMessage(notice("There is no user with that name!"));
                        //   $this->_redirect($this->view->url(array("message_action"=>"compose"),"messages"));
                        // }

                        // this is only model to user !!!!
                        $post['id_sender'] = $_SESSION['user']['id'];
                        $post['sender_type'] = $_SESSION['group'];
                        // $post['id_receiver'] = $the_user->id;
                        // $post['receiver_type'] = 'user';
                        $post['send_date'] = time();

                        unset($post['sendtouser']);
                        unset($post['send']);

                        /* bad words filters */

                        $this->load("bad_words");
                        $badWords = $this->bad_words->getAllArray();
                        array_walk($post, 'badWords', $badWords);

                        /* end bad words filter */

                        $this->messages->insert($post);

                        $message = "Message sent.";

                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "model", $_SESSION['user']['id'], 'user', "model-messages-send", $message, 1, getUserIp());

                        $this->_helper->FlashMessenger->addMessage(notice($message));

                        $addNotification = array(
                            "id_from" => $_SESSION['user']['id'],
                            "type_from" => $_SESSION['group'],
                            "id_to" => $post['id_receiver'],
                            "type_to" => $post['receiver_type'],
                            "type" => "new_message",
                            "notification" => "New message from " . $_SESSION['user']['screen_name'],
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $this->messages->getAdapter()->lastInsertId()
                        );
                        $this->addNotification($addNotification, $post['id_receiver']);


                        $this->_redirect($this->view->url(array("message_action" => "outbox"), "messages-moderator"));
                    }

                    if ($post['reply'] == 'Reply') {
                        $this->_data["replyto"] = array("id" => (int)$post["userid"], "type" => $post["usertype"], "name" => $post["username"]);
                    }
                    // $this->_redirect($this->view->url(array("message_action"=>"compose"),"messages-model"));
                }

                break;

            default:
                $this->_redirect('/404/');
                break;
        }

    }

    public function notificationsettingsAction()
    {

        $this->load("user_notifications_mail");
        $this->load("user_notifications_type");
        if ($this->request->isPost()) {

            $post = $this->_request->getPost();
            unset($post["settings"]["all"]);
            $this->user_notifications_mail->savePermissions($post["settings"], $_SESSION["group"], $_SESSION["user"]["id"]);

            $notice_msq = "Changes saved!";
            $this->_helper->FlashMessenger->addMessage(notice($notice_msq));

        }

        $this->_data["settings"] = $this->user_notifications_type->getAll();
        $userSettings = $this->user_notifications_mail->getPermissions($_SESSION["group"], $_SESSION["user"]["id"]);

        $settings = array();
        if ($userSettings) {
            foreach ($userSettings as $set) {
                $settings[] = $set->notification_type;
            }
        }

        $this->_data["userSettings"] = $settings;
    }

    public function docviewAction()
    {

        $params = $this->params;
        if ($params["id"]) {
            $id = (int)$params["id"];
        }

        $this->load("user_notifications");
        $this->load("photos");

        $photo = $this->photos->getPhotoById($id);

        $post = $this->_request->getPost();
        if ($post) {
            if ($post["activate"]) {
                $active = 1;
                $m = "activated";
                $nType = "approve_doc";
            } elseif ($post["rejected"]) {
                $active = 0;
                $m = "rejected";
                $nType = "reject_doc";
            } elseif ($post["pending"]) {
                $active = 2;
                $m = "pended";
                $nType = "pended_doc";
            }
            $this->photos->update(array("active" => $active), "id=" . $id);

            $this->user_notifications->addNotification(
                $id_from = $_SESSION["user"]["id"],
                $from_type = $_SESSION["group"],
                $id_to = $photo->id_model,
                $to_type = "model",
                $type = "upload_doc",
                $notification = "Moderator {$m} your " . ucwords(str_replace("_", " ", $photo->type)),
                $read = 0,
                $ip = $_SERVER["REMOTE_ADDR"],
                $resource = $photo->id
            );

            $addNotification = array(
                "id_from" => $_SESSION["user"]["id"],
                "type_from" => $_SESSION["group"],
                "id_to" => $photo->id_model,
                "type_to" => "model",
                "type" => $nType,
                "notification" => "Moderator {$m} your " . ucwords(str_replace("_", " ", $photo->type)),
                "ip" => $_SERVER["REMOTE_ADDR"],
                "date" => time(),
                "resource" => $photo->id
            );

            $this->addNotification($addNotification, "moderator");

            $this->_redirect($this->view->url(array(), "moderator-docview"));
        }

        $this->_data["file"] = $photo;
    }

    public function modelorderAction()
    {
        $this->load('model');
        $details = $this->model->getModels();
        $this->view->details = $details;
    }

    public function loginasAction()
    {

        $params = $this->params;
        $id = $params["id"];
        $type = $params["type"];

        switch ($type) {
            case "model":
                if ($_SESSION["user"]["id"] != "0") $this->_redirect($this->view->url(array(), "admin"));
                $_SESSION["role_switch"] = $_SESSION["user"]["id"];
                Auth::loginAs($id, "model");
                $this->_redirect('/admin/user/impersonate/'.$id);
                $this->_redirect($this->view->url(array(), "model-index"));
                break;
            case "moderator":
                if (!isset($_SESSION["role_switch"]) || $_SESSION["role_switch"] != "0") $this->_redirect($this->view->url(array(), "admin"));
                Auth::loginAs("0", "moderator");
                unset($_SESSION["role_switch"]);
                $this->_redirect('/admin/user/unimpersonate');
                $this->_redirect($this->view->url(array("type" => "active"), "show-model"));
                break;
        }
    }

    public function watermarkAction()
    {

        $post = $this->request->getPost();
        if ($post) {
            $photo_dir = APPLICATION_PATH . '/../../public/uploads/watermarks/';
            $this->load('upload');
            $this->load('config');
            if (isset($_FILES["watermark"]) && !empty($_FILES["watermark"]["name"])) {
                $upload = $this->upload->uploadWatermark($photo_dir, 'watermark');
                if ($upload['status'] == 'success') {
                    $this->config->update(array("val" => $upload['file']), ("var='photo_watermark'"));
                    $notice_msq = "File saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
                }

            }

            if (isset($_FILES["videologo"]) && !empty($_FILES["videologo"]["name"])) {
                $upload = $this->upload->uploadWatermark($photo_dir, 'videologo');
                if ($upload['status'] == 'success') {
                    $this->config->update(array("val" => $upload['file']), "var='video_logo'");
                    $notice_msq = "File saved!";
                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
                }
            }

            $this->_redirect($this->view->url(array(""), "system-watermarks"));

        }
    }

    public function badWordsAction()
    {

        $this->load("bad_words");

        $request = $this->_request;
        $post = $request->getPost();
        if ($post) {
            if (isset($post["save"])) {

                unset($post["save"]);
                $this->bad_words->insert($post);
                $this->_helper->FlashMessenger->addMessage(notice("Word saved!"));
                $this->_redirect("/admin/bad-words");
            }
            if (isset($post["mark_delete"])) {
                $this->bad_words->deleteMultiple($post["multiple_select"]);
                $this->_helper->FlashMessenger->addMessage(notice("Words deleted!"));
                $this->_redirect("/admin/bad-words");
            }
        }


        $this->_data["badWords"] = $this->bad_words->fetchAll($this->bad_words->select());

    }

}