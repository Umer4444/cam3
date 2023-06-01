<?php

use PerfectWeb\Core\Utils\Status;

class BlogController extends App_Controller_Action
{

    private $layout_control = null;
    private $route_name = null;

    public function init()
    {
        $this->_data["route_name"] = $this->route_name = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        if(endsWith($this->route_name, "-frontend"))
        {
            $this->initIndex();
            $this->layout_control = "frontend";
        }
        elseif(endsWith($this->route_name, "-performer-backend"))
        {
            $this->initModel();
            $this->layout_control = "performer";
        }
        elseif(endsWith($this->route_name, "-moderator-backend"))
        {
            $this->initAdmin();
            $this->layout_control = "moderator";
        }
    }

    private function initModel()
    {

        parent::init();
        $action = $this->_request->action;


        $this->load("user_notifications");
        $this->_data["notification_count"] = $this->user_notifications->getNewNotificationCount("model", $_SESSION["user"]["id"], $_SESSION["user"]["last_notification"], $this->acl->isAllowed($_SESSION['group'],"all_resources","view"));
        unset($this->user_notifications);

        //load development pages menu
        $this->load('static_pages');

        $parentPagesTop = $this->static_pages->getPages('backend');
        $this->_data['pages'] = array();

        foreach ($parentPagesTop as $pageTop) {
            $childrenTop = array();
            $parentPagesLvl1  = $this->static_pages->getPages('backend', $pageTop->page);
            foreach ($parentPagesLvl1 as $pageLvl1) {
                $childrenTop[] = array("page" => $pageLvl1->page, "title" => $pageLvl1->title);
            }
            $this->_data['pages'][] = array("page" => $pageTop->page, "title" => $pageTop->title, 'children' => $childrenTop);
        }

        if(Auth::isModel()){
            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'],"all_resources","view"));
            unset($this->messages);
        }

    }

    private function initIndex()
    {
        parent::init();

        if($this->_request->id_model)
            $id_model = $this->_request->id_model;
        elseif(isset($_SESSION["website"]["id_model"]))
            $id_model = $_SESSION["website"]["id_model"];
        else
            $id_model = false;

        $this->_data["id_model"] = $id_model;
        $this->_data["params"] = $this->_request;


       if(Auth::isUser()){

            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'],"all_resources","view"));
            unset($this->messages);
        }
     }

    private function initAdmin()
     {

        //if is model website, redirect to 404
       if(isset($_SESSION["website"]["url"]) && !empty($_SESSION["website"]["url"]))
            $this->_redirect("/404");

        parent::init();
        $action = $this->_request->action;
        if(!isset($_SESSION["role_switch"]) && !Auth::isModerator() && $action != 'login' && $action != 'pwreset') {
            $this->redirectToLogin('admin', $this->getRequest()->getRequestUri());
        }

        $this->load("user_notifications");
        $this->_data["notification_count"] = $this->user_notifications->getNewNotificationCount("moderator", $_SESSION["user"]["id"], $_SESSION["user"]["last_notification"], false);
        unset($this->user_notifications);

        if(Auth::isModerator()){
            $this->load("moderator");
            $this->_data["moderator"] = $this->moderator->fetchRow($this->moderator->select()->where("id=?", user()->id));
            $this->load("messages");
            $this->_data['unread_count']    = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group'], $this->acl->isAllowed($_SESSION['group'],"all_resources","view"));
            unset($this->messages);
        }

     }

    public function postslistAction()
    {
        if(strpos($this->route_name, '-category-') !== false) $this->_data["id_category"] = $this->params["id_category"];
        else $this->_data["id_category"] = null;

        $this->load("blog_posts");
        $this->_data['profile_type'] = $this->params['controller'];

        $page = $this->params["page"];

        if(Auth::isModel()) {
            $id_model = user()->id;
        }

        $this->_data["posts"] = $this->blog_posts->getAllPosts(array(), $id_model, false, false, false);

        $nr = 12;
        $paginator = Zend_Paginator::factory($this->_data["posts"]);
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        // send data to view, load view
        $this->view->assign($this->_data);

        $this->render("blog-performer-backend");


    }

    public function postviewAction()
    {
       $this->load("blog_posts");

       switch($this->layout_control){
            case "moderator":
            case "performer":

                $this->load("blog_posts");
                $id_post = ($this->params["id_post"] ? $this->params["id_post"] : $this->params["id_item"] );

                $this->_data["article"] = $this->blog_posts->getById($id_post);

                if(!$this->_data["article"]->id ) $this->_redirect("/404");

                if($this->_request->isPost()){
                    $post = $this->_request->getPost();

                    if($this->_data["article"]->status != 1 && (isset($post["acceptButton"]) || isset($post["denyButton"]))) {

                        if(Auth::isModerator() && (user()->id == 0 || $this->_data["article"]->id_moderator == user()->id)) {
                            if(isset($post["denyButton"])){
                                $this->_helper->FlashMessenger->addMessage(notice("Blog post denied!"));
                                $this->blog_posts->update(array("status" => "-1"), "id=".$this->_data["article"]->id);
                            }
                            elseif(isset($post["acceptButton"])){
                                $this->_helper->FlashMessenger->addMessage(notice("Blog post accepted!"));
                                $this->blog_posts->update(array("status" => "1"), "id=".$this->_data["article"]->id);
                            }
                        }

                        $url = $this->view->url(array("name" => $this->params["name"], "id_model" => $this->_data["article"]->id_model), "blog-list-moderator-backend");
                        $this->_redirect($url);
                     }
                }
                // send data to view, load view
                $this->view->assign($this->_data);

                if($this->layout_control == "performer")
                    $this->render("post-view-performer-backend");
                elseif($this->layout_control == "moderator")
                    $this->render("post-view-moderator-backend");

                break;
       }

    }

    public function postunlockAction(){

        if(!Auth::isLogged()) {
            $this->_redirect("/login");
        }else{

            $this->load("blog_posts");

            if((isset($this->params["id_post"]) || isset($this->params["id_item"])) && isset($this->params["title"])){
                $id_post = ($this->params["id_post"] ? $this->params["id_post"] : $this->params["id_item"] );
                $this->_data["article"] = $this->blog_posts->getById($id_post);

                $this->_data["post_chips"] = 0;

                //check if article is bought, and set price for article
                if(!isset($this->_data["article"]->purchased) || empty($this->_data["article"]->purchased)){

                    $types = (explode(',', $this->_data["article"]->type));
                    $chips = (explode(',', $this->_data["article"]->chips));
                    $dates = (explode(',', $this->_data["article"]->date));

                    $this->_data["post_chips"] = array_search('everyone', $types);
                    $this->_data["post_chips"] = (!is_null($this->_data["post_chips"]) ? $chips[$this->_data["post_chips"]] : null);
                    if(!$this->_data["post_chips"] || $this->_data["post_chips"] == 0){
                        if(Auth::isUser()){
                            $this->_data["post_chips"] = array_search('user', $types);
                            if($this->_data["post_chips"])
                                $this->_data["post_chips"] = $chips[$this->_data["post_chips"]];
                        } elseif(Auth::isModel()){
                            $this->_data["post_chips"] = array_search('model', $types);
                            if($this->_data["post_chips"])
                                $this->_data["post_chips"] = $chips[$this->_data["post_chips"]];
                        }
                    }



                    if(user()->chips < $this->_data["post_chips"]) {
                    //not enough chips, redirect

                    }
                    if ($this->_data["post_chips"] > 0) {

                        //$this->load("blog_purchase");
                        //$this->blog_purchase->purchasePost($this->_data["article"]->id);

                        //add purchased to table
                        $this->load("purchased_content")->addItem(array("id" => $this->_data["article"]->id, "type" => "blog_post", "amount" => $this->_data["post_chips"]));

                        if($_SESSION["group"] == "user"){
                             $this->load("user");
                             $this->user->update(array("chips" => new Zend_Db_Expr("chips - ".(int)$this->_data["post_chips"])), "id=".(int)user()->id);
                        }else if($_SESSION["group"] == "model"){
                             $this->load("model");
                             $this->model->update(array("chips" => new Zend_Db_Expr("chips - ".(int)$this->_data["post_chips"])), "id=".(int)user()->id);
                        }
                         $_SESSION["user"]["chips"] = user()->chips = user()->chips - $this->_data["post_chips"];

                        $this->_helper->FlashMessenger->addMessage(notice("Blog post unlocked!"));

                        $url = $this->view->url(array("name" => $this->params["name"], "id_model" => $this->_data["article"]->id_model, "title" => ro_slug($this->_data["article"]->title), "id_post" => $this->_data["article"]->id), "blog-post-frontend");

                        $this->_redirect($url);
                    }
                }
            }
        }
        $this->_redirect($url);
    }

    public function postaddAction(){

        $this->load("blog_categories");
        $this->load("model_moderator");
        $this->load("blog_posts");
        $this->load("blog_access");
        $this->load("photos");

        $edit = false;

        $id_model = user()->id;
        $redirect_url = $this->view->url(array(),"blog-performer-backend", true);

        if(isset($this->params["id_post"])){
            $this->_data["article"] = $this->blog_posts->getById($this->params["id_post"]);

           //if(!$this->_data["article"]->id) $this->_redirect("/404/");

            /*if((Auth::isModel() && $this->_data["article"]->user != user()->id) || (Auth::isModerator() && !(user()->id
                        == 0 || user()->id == $this->_data["article"]->id_moderator)) ){
                $this->_redirect("/404/");
            }*/

            $id_post = $this->_data["article"]->id;

            $edit = true;

            $id_model = $this->_data["article"]->user;
        }

        $this->_data["categories"] = $this->blog_categories->getAllByModelArray($id_model);

        //$this->_data["model_moderator"] = $this->model_moderator->getModelModerator($id_model);

        $post = $this->_request->getPost();

        if(isset($post["save_unfinished"]) || isset($post["save_moderation"]) || isset($post["save"])) {

           /* if(!$post['title'] || !$post['content'] || empty($post['title']) || empty($post['content'])){
                $this->_helper->FlashMessenger->addMessage(notice('Please add title and content'));
                $this->_redirect($this->view->url(array(),"blog-post-add-performer-backend"));
            }*/

            $blogPost["title"]      =  $post["title"];
            $blogPost["slug"]      =  str_replace([' ', '/'], '-', strtolower($post["title"]));
            $blogPost["category"]   =  $post["category"];
            $blogPost["tags"]       =  $post["tags"];
            $blogPost["content"]    =  $post["content"];
            $blogPost["user"]   =  $id_model;
            $blogPost["posted_on"]   =  date('Y-m-d H:i:s');


            if (isset($post["save_moderation"])) {
                $blogPost["status"]     =  Status::PENDING;
            }
            elseif (isset($post["save_unfinished"])) {
                $blogPost["status"]     =  Status::DRAFT;
            }
            else {
                $blogPost["status"]     =  (isset($post["status"])   ? $post["status"] : Status::DRAFT);
            }


            $blogPost["featured"]   =  (isset($post["featured"]) ? $post["featured"] : "0");
            //save latter send for approval

            if($edit){
                $this->blog_posts->update($blogPost, "id=".(int)$this->_data["article"]->id);
            }
            else {
                $id_post = $this->blog_posts->insert($blogPost);
            }

            $this->load("blog_access");

            $blog_dir = "blogs";
            $_dir ='/uploads/users/'.$id_model.'/'.$blog_dir.'/';
            $upload_dir = APPLICATION_PATH . '/../../public/uploads/users/'.$id_model.'/'.$blog_dir.'/';

            $this->load('upload');

            if (isset($_FILES["cover_image"]) && !empty($_FILES["cover_image"]["name"])) {

                $upload = $this->upload->uploadPhoto($upload_dir, 'cover_image');

                if($upload['status'] == 'success'){

                    $filename =  $upload['file'];

                    $this->upload->resize_image($upload_dir.$filename, $upload_dir.$filename, 800, 600,getcwd().'/public'.config()->photo_watermark);
                    /*$this->upload->create_square_image($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_t'.substr($filename,-4), 190);*/
                    /*$this->upload->resize_image_proportional($upload_dir . $filename, $upload_dir . $filename, 287, 287);*/

                    $this->photos->delete("type='".\Images\Entity\Photo::SMALL_COVER."' and reference_id=".$id_post);
                    $this->photos->addPhoto(array(
                        "reference_id" => $id_post,
                        "user" => $id_model,
                        "filename" =>  $_dir.$filename,
                        "status" => 1, "type" =>
                            \Images\Entity\Photo::SMALL_COVER,
                        'entity' => \Images\Entity\BlogImage::class
                    ));
                    $id_photo = db()->lastInsertId() ;

                }else{
                    echo 'error uploading file cover image!';
                }
            }

            if(isset($_FILES["full_image"]) && !empty($_FILES["full_image"]["name"])){

                $upload2 = $this->upload->uploadPhoto($upload_dir,'full_image');

                if($upload2['status'] == 'success'){

                    $filename = $upload2["file"];

                    $this->upload->resize_image($upload_dir.$filename, $upload_dir.$filename, 800, 600, getcwd().'/public'.config()->photo_watermark);
                    /*$this->upload->create_square_image($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_t'.substr($filename,-4), 190);*/
                    /*$this->upload->resize_image_proportional($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_tt'.substr($filename,-4), 190, 140);*/
                    /*$this->upload->resize_image_proportional($upload_dir.$filename, $upload_dir.substr($filename,0,-4).'_t'.substr($filename,-4), 190, 140, 1);*/

                    /*db()->query("insert into photos set ".db()->quoteInto("id_model=?", $_SESSION['user']['id']).",
                                                    ".db()->quoteInto("filename=?", $filename).", "
                                                    //.db()->quoteInto("caption=?",substr($post['Filename'],0,-4)).", "
                                                    ."id_show='".$id_post."',
                                                    active=1,
                                                    type='post_full_cover'
                                                    ");*/
                    $this->load("photos");
                    $this->photos->delete("type='".\Images\Entity\Photo::BIG_COVER."' and reference_id=".$id_post);
                    $this->photos->addPhoto(
                            array(
                                "reference_id" => $id_post,
                                "user" => $id_model,
                                "filename" => $_dir.$filename,
                                "status" => 1,
                                 "type" =>\Images\Entity\Photo::BIG_COVER,
                        'entity' => \Images\Entity\BlogImage::class)
                    );
                    $id_photo = db()->lastInsertId() ;

                }else{
                    echo 'Error uploading file full image!';
                }
            }

            if(isset($post["save_moderation"])){

                $message = "Performer ".(user()->screen_name)." submited post: " . $blogPost["title"];
                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "model", $_SESSION['user']['id'], 'user', "model-blogpost-add", $message, 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice($message));

                $addNotification = array(
                            "id_from"       => $_SESSION['user']['id'],
                            "type_from"     => $_SESSION['group'],
                            "id_to"         => (user()->id_moderator ? user()->id_moderator : 0),
                            "type_to"       => "moderator",
                            "type"          => "blog_post",
                            "notification"  => $message,
                            "ip"            => $_SERVER["REMOTE_ADDR"],
                            "date"          => time(),
                            "resource"      => $id_post,
                            "linked_resource" => $_SESSION['user']['id'],
                        );
                $this->addNotification($addNotification, "admin");
                $this->addNotification($addNotification, "moderator");
            }

            $this->_helper->FlashMessenger->addMessage(notice("Post saved!"));
            $this->_redirect($redirect_url);
        }

        $this->view->assign($this->_data);

       $this->render("post-add-performer");

    }

    public function categorieslistAction()
    {
        if($this->layout_control == "frontend")
            $this->_redirect("/404");

        $this->load("blog_categories");
        $request = $this->_request;
        $post = $request->getPost();
        if($post )  {
            $this->blog_categories->deleteMultiple($post["multiple_select"]);
        }

        $this->_data["categories"] = $this->blog_categories->getByModelId(user()->id);

        $this->view->assign($this->_data);
        switch($this->layout_control){
              case "performer":
                    $this->render("blog-categories-performer-backend");
              break;
        }
    }

    public function categoryaddAction()
    {
        if($this->layout_control == "frontend")
            $this->_redirect("/404");

        $this->load("blog_categories");

        $request = $this->_request;
        $post = $request->getPost();

        if($post){

            if(!$post['title'] || empty($post['title'])){
                $this->_helper->FlashMessenger->addMessage(notice('Please add title '));
                $this->_redirect($this->view->url(array(), "blog-category-add-performer-backend"));
            }

            $cat = array();
            $cat["name"] = $post["title"];
            $cat["parent_id"] = $post["parent_id"] ?: null ;
            $cat["user"] = user()->id;
            $cat["entity"] = 'blog';

            $this->blog_categories->insert($cat);
            $this->_helper->FlashMessenger->addMessage(notice('Category added'));
            $this->_redirect('/admin/blog/categories/list');
        }

        $this->_data["categories"] = $this->blog_categories->getAllByModelArray(user()->id);

        $this->view->assign($this->_data);
        switch($this->layout_control){
              case "performer":
                    $this->render("category-add-performer-backend");
              break;
        }

    }

    public function postmoderateAction(){

            if(!Auth::isModerator() || !isset($this->params["id_post"])) $this->_redirect("/404");
            $article = $this->load("blog_posts")->getById($this->params["id_post"]);
            if(!$this->_request->isPost() || !$article->id || ($article->id_moderator != user()->id && user()->id != 0)) $this->_redirect("/404");

           $post = $this->_request->getPost();
            if(isset($post["denyButton"])){
                $this->blog_posts->update(array("status" => -1), "id=".$article->id);

                $addNotification = array(
                    "id_from"       => $_SESSION['user']['id'],
                    "type_from"     => $_SESSION['group'],
                    "id_to"         => $article->id_model,
                    "type_to"       => "model",
                    "type"          => "deny_post",
                    "notification"  => 'Post "'.$article->title.'" denied',
                    "ip"            => $_SERVER["REMOTE_ADDR"],
                    "date"          => time(),
                    "resource"      => $article->id
                );
                $this->addNotification($addNotification, $article->id_model);
                $this->_helper->FlashMessenger->addMessage(notice("Post denied!", false));
            }
            if(isset($post["acceptButton"])){
                $this->blog_posts->update(array("status" => 1), "id=".$article->id);

                $addNotification = array(
                    "id_from"       => $_SESSION['user']['id'],
                    "type_from"     => $_SESSION['group'],
                    "id_to"         => $article->id_model,
                    "type_to"       => "model",
                    "type"          => "accept_post",
                    "notification"  => 'Post "'.$article->title.'" accepted',
                    "ip"            => $_SERVER["REMOTE_ADDR"],
                    "date"          => time(),
                    "resource"      => $article->id
                );
                $this->addNotification($addNotification, $article->id_model);

                $this->_helper->FlashMessenger->addMessage(notice("Post accepted!"));

                // add model action
                $this->load("model_actions");
                $this->model_actions->actionAdd("blog", $article->id_model);
            }

            $this->_redirect($this->view->url(array("id_model" => $article->id_model, "name" => $article->screen_name, "id_post" => $article->id, "title" => slug($article->title)), "blog-post-view-moderator-backend"));
            exit;

        }

}