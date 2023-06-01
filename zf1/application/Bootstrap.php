<?php

use Zf2for1\Application\Zf2Bootstrap;

class Bootstrap extends Zf2Bootstrap
{

    private $eventManager;

    protected function _initFunctions(){

        Zend_Loader::loadFile(APPLICATION_PATH."/library/functions.php", null, true);
        Zend_Loader::loadFile(APPLICATION_PATH."/models/auth.php", null, true);

    }

    protected function _initDbCache(){

        $frontend = array(
                       'lifetime' => 3600 * 24,
                       'automatic_serialization' => true,
                       'caching' => false,
                       'cache_id_prefix' => "dbCache"
                       );

        $backend = array('cache_dir' => APPLICATION_PATH."/../../data/cache/");

        $this->dbCache = Zend_Cache::factory('Core','File',$frontend,$backend);

        $frontend['cache_id_prefix'] = "metadataCache";

        $this->metadataCache = Zend_Cache::factory('Core', 'File', $frontend, $backend); // need to be separate cause will conflict with data cache

        Zend_Registry::set("dbCache", $this->dbCache);

        return $this->dbCache;

    }

    protected function _initSession(){

	    $sid = session_id();
	    // compat for zf2-1
        if(!empty($sid)) {
	        Zend_Session::$_unitTestEnabled = true; // workaround
	        Zend_Session::setId($sid);
        }
        else Zend_Session::start();

    }

    protected function _initDb(){

        $this->db = $this->getPluginResource('db')->getDbAdapter();

        Zend_Db_Table::setDefaultAdapter($this->db);
        Zend_Db_Table_Abstract::setDefaultMetadataCache($this->metadataCache);
        Zend_Registry::set("db", $this->db);

        return $this->db;

    }

    protected function _initLog(){

        $this->log = new App_Log();

          if(APPLICATION_ENV == "production"){

            //Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp('smtp.gmail.com', array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'sda', 'password' => 'dsadsadsa')));

            $mail = new Zend_Mail();
            $mail->setFrom('error.dev')
                 ->addTo('razvan.moldovan@perfectweb.ro')
                 ->addTo('kaius.plesa@perfectweb.ro')
                 ->addTo('debug@perfectweb.ro');

            $writer = new Zend_Log_Writer_Mail($mail);
            $this->log->addWriter($writer->setSubjectPrependText('Erori pe chat ip:'.$_SERVER['REMOTE_ADDR']));

            // error handling
            $this->log->registerErrorHandler();
            set_exception_handler("exceptionHandler");
            register_shutdown_function("fatalErrorHandler");

        }
        else $this->log->addWriter(new Zend_Log_Writer_Null);


        if(APPLICATION_ENV == "development" ){

            /*$mail = new Zend_Mail();
            $mail->setFrom('error.dev')
                ->addTo('ionut.fechete@perfectweb.ro')
                ->addTo('razvan.moldovan@perfectweb.ro')
                ->addTo('kaius.plesa@perfectweb.ro')
                ->addTo('debug@perfectweb.ro');

            $writer = new Zend_Log_Writer_Mail($mail);
            $this->log->addWriter($writer->setSubjectPrependText('Erori pe chat ip:'.$_SERVER['REMOTE_ADDR']));*/


            // error handling
            /*$this->log->registerErrorHandler();
            register_shutdown_function("fatalErrorHandler");*/

        }

        $this->log->addWriter(new Zend_Log_Writer_Syslog(array("facility" => LOG_USER)));
        $this->log->addWriter(new Zend_Log_Writer_Stream('php://stderr'));

        //set_exception_handler("exceptionHandler");

        Zend_Registry::set("log", $this->log);

        return $this->log;

    }

    protected function _initConfig(){

        Zend_Loader::loadFile(APPLICATION_PATH."/models/config.php", null, true);

        $config = new Config();

        if(empty($_SERVER["REMOTE_ADDR"])) $_SERVER["REMOTE_ADDR"] = '127.0.0.1';

        $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV, array("allowModifications" => true));

        foreach($config->fetchAll() as $row){
            $this->config->{$row->var} = $row->val;
        }
        unset($config);


        Zend_Loader::loadFile(APPLICATION_PATH."/models/timezones.php", null, true);
        $this->timezones = new Timezones();
        $gmt =  str_replace('.0','',$this->timezones->getGMT($this->config->timezone)->GMT);

        //set timezon to GMT (+0) so we can change GMT acording to this timezone
        date_default_timezone_set("GMT");
/*        //get server time zone
        $dateTimeZone = new DateTimeZone(date_default_timezone_get ());
        $server_time = new DateTime('now', $dateTimeZone);
        $server_offset = $dateTimeZone->getOffset( $server_time ) / 3600;*/

        //use gmt selected by admin and add offset
        //$gmt = $gmt - $server_offset;
/*        if($server_offset>=0)
            $server_offset = "+".$server_offset;*/

        //$gmt = (string)$server_offset;

        ini_set('date.timezone', 'Etc/GMT'.$gmt);

        $this->config->gmt = $gmt;
        //set registry
        Zend_Registry::set("config", $this->config);

        unset($gmt);
        unset($this->timezones);

        return $this->config;

    }

    protected function _initFront(){

        $this->bootstrap('FrontController');
        $this->front = $this->getResource('FrontController');

        return $this->front;

    }

    protected function _initRequest(){

        $this->request = new Zend_Controller_Request_Http();
        $this->front->setRequest($this->request);
        Zend_Registry::set("front", $this->front);

        return $this->request;

    }

    /**
    * Initialize the routes we need
    *
    */
    protected function _initRoutes(){

        $this->router = $this->front->getRouter();

        $this->router
         /**/->addRoute('contact',              new Zend_Controller_Router_Route('/contact/:page', array('controller'=> 'index','action' => 'contact', "page" => null)))
         /**/->addRoute('premieres',            new Zend_Controller_Router_Route('/premieres/', array('controller' => 'index','action' => 'premieres')))
         ->addRoute('premieres-page',       new Zend_Controller_Router_Route('/premieres/page/:page', array('controller' => 'index','action' => 'premieres')))
         ->addRoute('premieres-page-date',  new Zend_Controller_Router_Route('/premieres/date/:date', array('controller' => 'index','action' => 'premieres')))
         ->addRoute('premieres-page-date-page', new Zend_Controller_Router_Route('/premieres/date/:date/page/:page', array('controller' => 'index','action' => 'premieres')))
         ->addRoute('premieres-past',       new Zend_Controller_Router_Route('/premieres/date/:date/id/:id/name/:name', array('controller' => 'index','action' => 'premieres')))
         ->addRoute('premieres-past-page',  new Zend_Controller_Router_Route('/premieres/date/:date/id/:id/name/:name/page/:page', array('controller' => 'index','action' => 'premieres')))

         /**/->addRoute('store',                new Zend_Controller_Router_Route('/store/', array('controller' => 'index','action' => 'store')))
         /**/->addRoute('auction',              new Zend_Controller_Router_Route('/auction/', array('controller' => 'index','action' => 'auction')))
         /**/->addRoute('lobby',                new Zend_Controller_Router_Route('/lobby/', array('controller' => 'index','action' => 'lobby')))
         /**/->addRoute('static-page',          new Zend_Controller_Router_Route('/page/:page', array('controller' => 'index','action' => 'page')))
         //->addRoute('presentations',        new Zend_Controller_Router_Route('/presentations/', array('controller' => 'index','action' => 'presentations')))
         ->addRoute('hall-of-fame',         new Zend_Controller_Router_Route('/hall-of-fame/', array('controller' => 'index','action' => 'hallOfFame')))
         ->addRoute('group-shows',          new Zend_Controller_Router_Route('/group-shows/', array('controller' => 'index','action' => 'groupShows')))
         /*1*/->addRoute('most-popular-room',    new Zend_Controller_Router_Route('/most-popular-room/', array('controller' => 'index','action' => 'mostPopularRoom')))
         ->addRoute('play',                 new Zend_Controller_Router_Route('/play/', array('controller' => 'index','action' => 'play')))

         ->addRoute('latest-news',         new Zend_Controller_Router_Route('latest-news/:id_news', array('controller' => 'index', 'action' => 'latest-news', "id_news" => null)))

         ->addRoute('messages',         new Zend_Controller_Router_Route('/admin/my-messages/:message_action', array('controller' => 'index', 'action' => 'messages')))

         ->addRoute('messages1',        new Zend_Controller_Router_Route('/admin/my-messages/:message_action/page/:page', array('controller' => 'index', 'action' => 'messages')))
         ->addRoute('messages-view',    new Zend_Controller_Router_Route('/admin/my-messages/view/:id', array('controller' => 'index', 'action' => 'viewMessage')))
         /**/->addRoute('models',           new Zend_Controller_Router_Route('/models/', array('controller' => 'index', 'action' => 'models', "page" => 1)))
         ->addRoute('models1',           new Zend_Controller_Router_Route('/models/page/:page', array('controller' => 'index', 'action' => 'models', "page" => 1)))

         //model profile
         /**/->addRoute('model-profile',    new Zend_Controller_Router_Route('/performer/:id_model/:name/:profile_type', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute('model-profile1',   new Zend_Controller_Router_Route('/performer/:profile_type/:id_model/:name/page/:page', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute('model-profile3',   new Zend_Controller_Router_Route('/performer/:profile_type/:video_type/:id_model/:name/page/:page', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute('model-profile2',   new Zend_Controller_Router_Route('/performer/:profile_type/:id_special_request/:id_model/:name', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute('model-profile4',    new Zend_Controller_Router_Route('/performer/:id_model/:name/:profile_type/:type_action/:id_item/:title', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute('model-profile-blog-category',    new Zend_Controller_Router_Route('/performer/:id_model/:name/:profile_type/:type_action/:id_item/:title', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute('model-profile5',    new Zend_Controller_Router_Route('/performer/:id_model/:name/:profile_type/:type_action/:id_item/:title/do/:purchase', array('controller' => 'index', 'action' => 'modelProfile')))

         // call model
         /**/->addRoute('model-profile-call',    new Zend_Controller_Router_Route('/performer/:id_model/:name/call', array('controller' => 'call', 'action' => 'make-call')))

         //->addRoute('pledges-frontend',             new Zend_Controller_Router_Route('/pledges', array('controller' => 'pledge', 'action' => 'listPledges', "page" => 1)))
         ->addRoute('pledges-page-frontend',        new Zend_Controller_Router_Route('/pledges/page/:page', array('controller' => 'pledge', 'action' => 'listPledges', "page" => 1)))
         ->addRoute('pledges-filter-frontend',      new Zend_Controller_Router_Route('/pledges/filter/*', array('controller' => 'pledge', 'action' => 'listPledges')))
         ->addRoute('pledge-view-frontend',         new Zend_Controller_Router_Route('/pledge/view/:id_pledge/:title', array('controller' => 'pledge', 'action' => 'viewPledge' )))
         ->addRoute('pledge-contribute-frontend',   new Zend_Controller_Router_Route('/pledge/:id_pledge/:title/contribute/:id_perk', array('controller' => 'pledge', 'action' => 'contributePledge', "id_perk" => null)))

         /*     blogs frontend NEW      */
         ->addRoute("blog-list-frontend",           new Zend_Controller_Router_Route("/performer/:id_model/:name/blog", array("controller" => "blog", "action" => "postsList" )))
         ->addRoute("blog-list-page-frontend",      new Zend_Controller_Router_Route("/performer/:id_model/:name/blog/page/:page", array("controller" => "blog", "action" => "postsList", "page" => 1 )))
         ->addRoute("blog-category-frontend",       new Zend_Controller_Router_Route("/performer/:id_model/:name/blog/category/:id_category/:title", array("controller" => "blog", "action" => "postsList" )))
         ->addRoute("blog-category-page-frontend",  new Zend_Controller_Router_Route("/performer/:id_model/:name/blog/category/:id_category/:title", array("controller" => "blog", "action" => "postsList", "page" => 1 )))
         ->addRoute("blog-post-frontend",           new Zend_Controller_Router_Route("/performer/:id_model/:name/blog/post/:id_post/:title", array("controller" => "blog", "action" => "postView" )))
         ->addRoute("blog-post-purchase-frontend",  new Zend_Controller_Router_Route("/performer/:id_model/:name/blog/post/:id_post/:title/do/:purchase", array("controller" => "blog", "action" => "postUnlock")))

         ->addRoute('model-profile-gallery',    new Zend_Controller_Router_Route('/gallery/:id_gallery/:name', array('controller' => 'index', 'action' => 'gallery')))
         ->addRoute('model-profile-gallery1',   new Zend_Controller_Router_Route('/gallery/:id_gallery/:name/page/:page', array('controller' => 'index', 'action' => 'gallery')))
         ->addRoute('model-profile-gallery2',   new Zend_Controller_Router_Route('/gallery/:id_gallery/:name/:action_type', array('controller' => 'index', 'action' => 'gallery')))

         ->addRoute('model-image-gallery',      new Zend_Controller_Router_Route('/image/:id_image', array('controller' => 'index', 'action' => 'image')))

         /**/->addRoute('watch',        new Zend_Controller_Router_Route('/performer/:id_model/:name/watch',array
         ('controller' => 'index','action' => 'watchModel', "popup" => false)))
         ->addRoute('watch-popup',  new Zend_Controller_Router_Route('/performer/:id_model/:name/popup',array('controller' => 'index','action' => 'watchModel', "popup" => true)))
         ->addRoute('watch-popup-d',  new Zend_Controller_Router_Route('/watch/:id_model/:name/popup',array('controller' => 'index','action' => 'watchModel', "popup" => true)))
         // for test

         ->addRoute('watch-solo', new Zend_Controller_Router_Route('/live-broadcast/:id_model', array('controller' => 'index','action' => 'watchsolo')))

         ->addRoute('watch-nav', new Zend_Controller_Router_Route('/watch/:id_model/:name/:nav/:position',array('controller' => 'index','action' => 'watchModel')))
         ->addRoute('video', new Zend_Controller_Router_Route('/video/:id/:name/',array('controller' => 'index','action' => 'video')))
         ->addRoute('videos', new Zend_Controller_Router_Route('/videos/:id/:name/',array('controller' => 'index','action' => 'videos')))
         ->addRoute('favorite', new Zend_Controller_Router_Route('/favorite-models/',array('controller' => 'index','action' => 'favorite')))
         ->addRoute('page404', new Zend_Controller_Router_Route('/404/',array('controller' => 'index','action' => 'page404')))
         ->addRoute('purchase-chips',   new Zend_Controller_Router_Route('/purchase-chips/',array('controller' => 'index','action' => 'purchaseChips')))
         ->addRoute('verify-user',  new Zend_Controller_Router_Route('/verify/code/:code', array('controller' => 'index', 'action' => 'verify')))
         ->addRoute('model-index',          new Zend_Controller_Router_Route('/performer/index/', array('controller' => 'model', 'action' => 'index')))
         ->addRoute('model-index2',         new Zend_Controller_Router_Route('/performer/index/page/:page', array('controller' => 'model', 'action' => 'index')))
         ->addRoute('model-index1',         new Zend_Controller_Router_Route('/performer/page/:page', array('controller' => 'model', 'action' => 'index')))
         ->addRoute('model-payment-info',         new Zend_Controller_Router_Route('/admin/payment/info', array ('controller' => 'model', 'action' => 'payment-info')))

         ->addRoute('model_pwreset',        new Zend_Controller_Router_Route('/performer/pwreset/', array('controller' => 'model', 'action' => 'pwreset')))
         ->addRoute('model_pwreset2',       new Zend_Controller_Router_Route('/performer/pwreset/:var', array('controller' => 'model', 'action' => 'pwreset')))
         /*->addRoute('model-rules',          new Zend_Controller_Router_Route('/admin/rules', array('controller' => 'model', 'action' => 'rules')))*/
         ->addRoute('model-static-pages',   new Zend_Controller_Router_Route('/pages/:parent', array('controller' =>
                                                                                                         'model','action' => 'page')))
         ->addRoute('verify-model',         new Zend_Controller_Router_Route('/performer/verify/code/:code', array('controller' => 'model', 'action' => 'verify')))
         ->addRoute('model-notes',          new Zend_Controller_Router_Route('/admin/my-notes/:action_type/:id_note', array('controller' => 'model', 'action' => 'notes', "action_type" => null, "id_note" => null)))

         ->addRoute('messages-model',                   new Zend_Controller_Router_Route('/admin/my-messages/:message_action', array('controller' => 'model', 'action' => 'messages')))
         ->addRoute('messages-model1',                  new Zend_Controller_Router_Route('/admin/my-messages/:message_action/page/:page', array('controller' => 'model', 'action' => 'messages')))
         ->addRoute('auto-responders-model',            new Zend_Controller_Router_Route('/admin/train/auto-responders', array('controller' => 'model', 'action' => 'trainAutoResponders')))
         ->addRoute('auto-responders-model1',           new Zend_Controller_Router_Route('/admin/train/auto-responders/page/:page', array('controller' => 'model', 'action' => 'trainAutoResponders')))
         ->addRoute('user-notes-model',                 new Zend_Controller_Router_Route('/admin/user-notes', array('controller' => 'model', 'action' => 'userNotes')))
         ->addRoute('user-notes-model1',                new Zend_Controller_Router_Route('/admin/user-notes/page/:page', array('controller' => 'model', 'action' => 'userNotes')))
         ->addRoute('model-media-upload',               new Zend_Controller_Router_Route('/admin/upload/:type', array('controller' => 'model', 'action' => 'upload')))
         ->addRoute('model-manage-videos',              new Zend_Controller_Router_Route('/admin/manage/videos',array('controller' => 'model', 'action' => 'manageVideos')))
         ->addRoute('model-manage-videos1',             new Zend_Controller_Router_Route('/performer/manageVideos/:type', array('controller' => 'model', 'action' => 'manageVideos')))
         /*->addRoute('model-manage-photos1',             new Zend_Controller_Router_Route('/admin/manage/photos/:type', array('controller' => 'model', 'action' => 'managePhotos')))*/
         ->addRoute('model-manage-photos2',             new Zend_Controller_Router_Route('/admin/managePhotos/:type/:id', array('controller' => 'model', 'action' => 'managePhotosEdit')))
         ->addRoute('model-manage-photos22',             new Zend_Controller_Router_Route('/admin/managePhotos',
                                                                                          array('controller' => 'model', 'action' => 'managePhotosEdit')))
         ->addRoute('model-media-record',               new Zend_Controller_Router_Route('/admin/record', array
         ('controller' => 'model', 'action' => 'recordMedia')))
         ->addRoute('model-schedule-events',            new Zend_Controller_Router_Route('/admin/schedule/events', array('controller' => 'model', 'action' => 'scheduleEvents')))
         ->addRoute('model-privacy-settings',           new Zend_Controller_Router_Route('/admin/privacy/settings', array('controller' => 'model', 'action' => 'privacySettings')))
         ->addRoute('model-privacy-settings-lists',     new Zend_Controller_Router_Route('/admin/privacy/settings/:type', array('controller' => 'model', 'action' => 'privacySettings')))
         ->addRoute('model-privacy-settings-manage',    new Zend_Controller_Router_Route('/admin/privacy/settings/:type', array('controller' => 'model', 'action' => 'privacySettings')))
         ->addRoute('model-privacy-settings-lists-page', new Zend_Controller_Router_Route('/admin/privacy/settings/:type/page/:page', array('controller' => 'model', 'action' => 'privacySettings')))

         //new zf1-zf2 routes
         ->addRoute('model-profile-settings', new Zend_Controller_Router_Route('/admin/profile/settings', array
         ('controller' => 'model', 'action' => 'profileSettings')))
         ->addRoute('model-account-settings', new Zend_Controller_Router_Route('/admin/account/settings', array
         ('controller' => 'model', 'action' => 'accountSettings')))
         ->addRoute('model-broadcast', new Zend_Controller_Router_Route('/admin/broadcast', array('controller' => 'model', 'action' => 'broadcast')))
         ->addRoute('model-chat-settings', new Zend_Controller_Router_Route('/admin/chat/sounds', array
         ('controller' => 'model', 'action' => 'chatSettings')))
         ->addRoute('model-quotes', new Zend_Controller_Router_Route('/admin/chat/quotes', array('controller' =>
                                                                                                     'model', 'action' => 'quotes')))
         /* new banners */
         ->addRoute('banner-list-performer-backend',        new Zend_Controller_Router_Route('/admin/manage/banners/list', array('controller' => 'banner', 'action' => 'list-banners', "page" => 1)))
         ->addRoute('banner-list-page-performer-backend',   new Zend_Controller_Router_Route('/admin/manage/banners/list/page/:page', array('controller' => 'banner', 'action' => 'list-banners', "page" => 1)))
         ->addRoute('banner-add-performer-backend',         new Zend_Controller_Router_Route
         ('/admin/manage/banners/add', array('controller' => 'banner', 'action' => 'add-banners')))
         ->addRoute('banner-edit-performer-backend',        new Zend_Controller_Router_Route('/admin/manage/banners/edit/:id_banner', array('controller' => 'banner', 'action' => 'add-banners')))
         ->addRoute('banner-delete-performer-backend',      new Zend_Controller_Router_Route('/admin/manage/banners/delete/:id_banner', array('controller' => 'banner', 'action' => 'delete-banners')))

       /* blog model new */
         ->addRoute('blog-performer-backend',                 new Zend_Controller_Router_Route('/admin/manage/blogs',                         array('controller' => 'blog', 'action' => 'postslist', "page" => 1)))
         ->addRoute('blog-page-performer-backend',            new Zend_Controller_Router_Route('/admin/manage/blogs/page/:page',               array('controller' => 'blog', 'action' => 'postslist', "page" => 1)))
         ->addRoute('blog-post-performer-backend',            new Zend_Controller_Router_Route('/admin/blog/post/view/:id_post/:title',     array('controller' => 'blog', 'action' => 'postview')))
         ->addRoute('blog-post-add-performer-backend',        new Zend_Controller_Router_Route('/admin/blog/post/add',                      array('controller' => 'blog', 'action' => 'postadd')))
         ->addRoute('blog-post-edit-performer-backend',       new Zend_Controller_Router_Route('/admin/blog/post/edit/:id_post',     array('controller' => 'blog', 'action' => 'postadd')))
         ->addRoute('blog-category-performer-backend',        new Zend_Controller_Router_Route('/admin/blog/categories/list',              array('controller' => 'blog', 'action' => 'categorieslist')))
         ->addRoute('blog-category-add-performer-backend',    new Zend_Controller_Router_Route('/admin/blog/categories/add/',               array('controller' => 'blog', 'action' => 'categoryadd')))
         ->addRoute('blog-category-edit-performer-backend',   new Zend_Controller_Router_Route('/admin/blog/categories/edit/:id/:title',    array('controller' => 'blog', 'action' => 'categoryadd')))

         /**/->addRoute('model-notifications-set',new Zend_Controller_Router_Route('/admin/notifications/settings', array('controller' => 'model', 'action' => 'notificationSettings')))
         /**/->addRoute('notifications',    new Zend_Controller_Router_Route('notifications', array('controller' => 'index', 'action' => 'notifications', "page" => 1)))
         /**/->addRoute('notifications-page',    new Zend_Controller_Router_Route('notifications', array('controller' => 'index', 'action' => 'notifications', "page" => 1)))

         /* NEW MODEL PLEDGES */
         // pledges new routes
         /**/->addRoute('pledge-list-performer-backend',        new Zend_Controller_Router_Route
         ('/admin/manage/pledges', array('controller' => 'pledge', 'action' => 'listPledges', "page" => 1)))
         /**/->addRoute('bad-word-filter',                      new Zend_Controller_Router_Route('/admin/bad-words-filter', array('controller' => 'model', 'action' => 'bad-word-filter')))
         /**/->addRoute('pledge-list-page-performer-backend',   new Zend_Controller_Router_Route('/admin/pledge/list/page/:page', array('controller' => 'pledge', 'action' => 'listPledges', "page" => 1)))
         /**/->addRoute('pledge-add-performer-backend',         new Zend_Controller_Router_Route('/admin/pledge/add', array('controller' => 'pledge', 'action' => 'addPledge')))
         /**/->addRoute('pledge-edit-performer-backend',        new Zend_Controller_Router_Route('/admin/pledge/edit/:id_pledge/:title', array('controller' => 'pledge', 'action' => 'addPledge')))
         /**/->addRoute('pledge-delete-performer-backend',      new Zend_Controller_Router_Route('/admin/pledge/delete/:id_pledge/:title', array('controller' => 'pledge', 'action' => 'deletePledge')))
         /**/->addRoute('pledge-photos-performer-backend',      new Zend_Controller_Router_Route('/admin/pledge/photos/:id_pledge/:title', array('controller' => 'pledge', 'action' => 'addPhotos')))
         ->addRoute('perk-add-performer-backend',           new Zend_Controller_Router_Route('/admin/pledge/perks/:id_pledge/add', array('controller' => 'pledge', 'action' => 'addPerk')))
         ->addRoute('perk-edit-performer-backend',          new Zend_Controller_Router_Route('/admin/pledge/perks/:id_pledge/edit/:id_perk/:title', array('controller' => 'pledge', 'action' => 'addPerk')))
         ->addRoute('pledge-add-update-performer-backend',      new Zend_Controller_Router_Route('/admin/pledge/update/:id_pledge/add', array('controller' => 'pledge', 'action' => 'addUpdate')))

         ->addRoute('model-notifications',          new Zend_Controller_Router_Route('/admin/notifications', array
         ('controller' => 'model', 'action' => 'notifications')))
         /**/->addRoute('model-notifications-page',     new Zend_Controller_Router_Route('/admin/notifications/page/:page', array('controller' => 'model', 'action' => 'notifications', "page" => "1")))

         /**
         *  admin routes
         */

         ->addRoute('admin',            new Zend_Controller_Router_Route('/admin/',array('controller' => 'admin','action' => 'index')))
         /*->addRoute('admin_pwreset',    new Zend_Controller_Router_Route('/admin/pwreset/', array('controller' => 'admin', 'action' => 'pwreset')))
         ->addRoute('admin_pwreset2',   new Zend_Controller_Router_Route('/admin/pwreset/:var', array('controller' => 'admin', 'action' => 'pwreset')))*/
         ->addRoute('show-model',       new Zend_Controller_Router_Route('/admin/models/:type', array('controller' => 'admin', 'action' => 'models')))
         ->addRoute('show-user',        new Zend_Controller_Router_Route('/admin/users/:type', array('controller' => 'admin', 'action' => 'users')))
         ->addRoute('show-moderator',   new Zend_Controller_Router_Route('/admin/moderators/:type', array('controller' => 'admin', 'action' => 'moderators')))
         ->addRoute('show-model1',      new Zend_Controller_Router_Route('/admin/models/:type/page/:page', array('controller' => 'admin', 'action' => 'models')))
         ->addRoute('show-user1',       new Zend_Controller_Router_Route('/admin/users/:type/page/:page', array('controller' => 'admin', 'action' => 'users')))
         ->addRoute('show-moderator1',  new Zend_Controller_Router_Route('/admin/moderators/:type/page/:page', array('controller' => 'admin', 'action' => 'moderators')))

         ->addRoute('manage-model',         new Zend_Controller_Router_Route('/admin/modelSettings/:id/:name/:manage', array('controller' => 'admin', 'action' => 'modelSettings')))
         ->addRoute('manage-user',          new Zend_Controller_Router_Route('/admin/userSettings/:id/:name/:manage', array('controller' => 'admin', 'action' => 'userSettings')))
         ->addRoute('manage-moderator',     new Zend_Controller_Router_Route('/admin/moderatorSettings/:id/:name/:manage', array('controller' => 'admin', 'action' => 'moderatorSettings')))
         ->addRoute('manage-model-account', new Zend_Controller_Router_Route('/admin/modelAccountSettings/:id/:name/:manage', array('controller' => 'admin', 'action' => 'modelAccountSettings')))


         /* admin  manage blog / new routes controller */

         ->addRoute('blog-list-moderator-backend',           new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/list',               array('controller' => 'blog', 'action' => 'postslist', "page" => 1)))
         ->addRoute('blog-list-page-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/list/page/:page',    array('controller' => 'blog', 'action' => 'postslist', "page" => 1)))
         ->addRoute('blog-post-view-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/post/view/:id_post/:title',    array('controller' => 'blog', 'action' => 'postview')))
         ->addRoute('blog-post-edit-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/post/edit/:id_post/:title',    array('controller' => 'blog', 'action' => 'postadd')))
         ->addRoute('blog-post-moderate-moderator-backend',  new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/post/moderate/:id_post/:title',    array('controller' => 'blog', 'action' => 'postmoderate')))
         ->addRoute('blog-filter-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/list/filter/:filter',    array('controller' => 'blog', 'action' => 'postslist', "page" => 1)))
         ->addRoute('blog-filter-page-moderator-backend', new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/blog/list/filter/:filter/page/:page',    array('controller' => 'blog', 'action' => 'postslist', "page" => 1)))

         // new admin PLEDES ROUTES
         ->addRoute('pledge-list-moderator-backend',        new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/list',     array('controller' => 'pledge', 'action' => 'listpledges', "page" => 1)))
         ->addRoute('pledge-list-page-moderator-backend',   new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/list',     array('controller' => 'pledge', 'action' => 'listpledges', "page" => 1)))
         ->addRoute('pledge-edit-moderator-backend',        new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/edit/:id_pledge', array('controller' => 'pledge', 'action' => 'addpledge')))
         ->addRoute('pledge-moderate-moderator-backend',    new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/moderate/:id_pledge', array('controller' => 'pledge', 'action' => 'moderatepledge')))
         ->addRoute('pledge-delete-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/delete/:id_pledge', array('controller' => 'pledge', 'action' => 'deletepledge')))
         ->addRoute('perk-add-moderator-backend',           new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/:id_pledge/perks/add', array('controller' => 'pledge', 'action' => 'addperk')))
         ->addRoute('perk-edit-moderator-backend',          new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/:id_pledge/perks/edit/:id_perk', array('controller' => 'pledge', 'action' => 'addperk')))
         ->addRoute('perk-moderate-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/pledge/:id_pledge/perks/moderate', array('controller' => 'pledge', 'action' => 'moderateperk')))

//         ->addRoute('manage-model-rates',       new Zend_Controller_Router_Route('/admin/modelRates/:id/:name/:manage', array('controller' => 'admin', 'action' => 'modelRates')))
         ->addRoute('manage-user-account',      new Zend_Controller_Router_Route('/admin/userAccountSettings/:id/:name/:manage', array('controller' => 'admin', 'action' => 'userAccountSettings')))
         ->addRoute('manage-moderator-account', new Zend_Controller_Router_Route('/admin/moderatorAccountSettings/:id/:name/:manage', array('controller' => 'admin', 'action' => 'moderatorAccountSettings')))
         ->addRoute('chat-settings',            new Zend_Controller_Router_Route('/admin/chat-settings', array('controller' => 'admin', 'action' => 'chatSettings')))
         ->addRoute('manage-notes',             new Zend_Controller_Router_Route('/admin/:type/notes/:id/:name/:manage', array('controller' => 'admin', 'action' => 'manageNotes')))
         ->addRoute('mod-announcements-edit',   new Zend_Controller_Router_Route('/admin/announcements/:type/:id', array('controller' => 'admin', 'action' => 'announcements')))
         ->addRoute('mod-announcements1',       new Zend_Controller_Router_Route('/admin/announcements/page/:page', array('controller' => 'admin', 'action' => 'announcements')))
         ->addRoute('mod-announcements',        new Zend_Controller_Router_Route('/admin/announcements/:type', array('controller' => 'admin', 'action' => 'announcements')))
         //
        // ->addRoute('mod-timezone', new Zend_Controller_Router_Route('/admin/timezone', array('controller' => 'admin', 'action' => 'timezoneEdit')))
         ->addRoute('system-settings',          new Zend_Controller_Router_Route('/admin/system-settings', array('controller' => 'admin', 'action' => 'systemSettings')))  //

         ->addRoute('revenue-stats',            new Zend_Controller_Router_Route('/admin/revenue/stats', array
         ('controller' => 'admin', 'action' => 'revenueStats')))

         ->addRoute('static-pages-edit',        new Zend_Controller_Router_Route('/admin/manage/pages', array
         ('controller' => 'admin', 'action' => 'pages')))
         ->addRoute('static-pages-edit1',       new Zend_Controller_Router_Route('/admin/manage/pages/:page', array('controller' => 'admin', 'action' => 'pages')))

         ->addRoute('email-templates',          new Zend_Controller_Router_Route('/admin/templates', array('controller' => 'admin','action' => 'templates')))

         ->addRoute('system-watermarks',        new Zend_Controller_Router_Route('/admin/watermarks', array('controller' => 'admin','action' => 'watermark')))

         ->addRoute('email-templates1',         new Zend_Controller_Router_Route('/admin/templates/:name', array('controller' => 'admin','action' => 'templates')))

         ->addRoute('development-pages-edit',   new Zend_Controller_Router_Route('/admin/manage/development', array('controller' => 'admin', 'action' => 'development')))
         ->addRoute('development-pages-add',    new Zend_Controller_Router_Route('/admin/manage/development/manage/:add', array('controller' => 'admin', 'action' => 'development')))

         ->addRoute('development-pages-manage', new Zend_Controller_Router_Route('/admin/manage/development/manage/:manage/page/:page', array('controller' => 'admin', 'action' =>'development')))
         ->addRoute('development-pages-edit1',  new Zend_Controller_Router_Route('/admin/manage/development/:page', array('controller' => 'admin', 'action' => 'development')))

         ->addRoute('manage-chips',             new Zend_Controller_Router_Route('/admin/manageChips/:user_type/:id/:name', array('controller' => 'admin', 'action' => 'manageChips')))

         ->addRoute('manage-rules1',                 new Zend_Controller_Router_Route('/rules', array('controller' => 'admin', 'action' => 'rules')))
         ->addRoute('manage-rules2',                 new Zend_Controller_Router_Route('/rules/:type', array
         ('controller' => 'admin', 'action' => 'rules')))

         //@todo this is disabled temporary
         //->addRoute('moderator-notifications',      new Zend_Controller_Router_Route('/admin/notifications/',array('controller' => 'admin', 'action' => 'notifications')))
         //->addRoute('moderator-notifications-page', new Zend_Controller_Router_Route('/admin/notifications/page/:page', array('controller' => 'admin', 'action' => 'notifications', "page" => "1")))
         //->addRoute('moderator-notifications-set',  new Zend_Controller_Router_Route('/admin/notifications/settings',array('controller' => 'admin', 'action' => 'notificationSettings')))

         ->addRoute('moderator-docview',            new Zend_Controller_Router_Route('/admin/docview/:id', array('controller' => 'admin', 'action' => 'docview')))
         ->addRoute('model-order',                  new Zend_Controller_Router_Route('/admin/model-order', array('controller' => 'admin', 'action' => 'modelOrder')))
         ->addRoute('admin-login-as',               new Zend_Controller_Router_Route('/admin/login-as/:type/:id', array('controller' => 'admin', 'action' => 'loginAs')))

         ->addRoute('messages-moderator',  new Zend_Controller_Router_Route('/admin/messages/:message_action', array('controller' => 'admin', 'action' => 'messages')))
         ->addRoute('messages-moderator1', new Zend_Controller_Router_Route('/admin/messages/:message_action/page/:page', array('controller' => 'admin', 'action' => 'messages')))
         //->addRoute('newsletter-add',       new Zend_Controller_Router_Route('/admin/newsletter-add', array('controller' => 'admin', 'action' => 'newsletterAdd')))

         ->addRoute('admin-banners',                     new Zend_Controller_Router_Route('/admin/banner-management', array('controller' => 'admin', 'action' => 'banner-management')))
         ->addRoute('banner-management',                     new Zend_Controller_Router_Route('/admin/banner-management/:id_model/:name', array('controller' => 'admin', 'action' => 'banner-management')))

         /* new banners */
         ->addRoute('banner-list-moderator-backend',        new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/banners/list', array('controller' => 'banner', 'action' => 'list-banners', 'page' => 1)))
         ->addRoute('banner-list-page-moderator-backend',   new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/banners/list/page/:page', array('controller' => 'banner', 'action' => 'list-banners', "page" => 1)))

         ->addRoute('banner-moderate-moderator-backend',    new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/banners/moderate', array('controller' => 'banner', 'action' => 'moderate-banners')))
         ->addRoute('banner-add-moderator-backend',         new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/banners/add', array('controller' => 'banner', 'action' => 'add-banners')))
         ->addRoute('banner-edit-moderator-backend',        new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/banners/edit/:id_banner', array('controller' => 'banner', 'action' => 'add-banners')))
         ->addRoute('banner-delete-moderator-backend',      new Zend_Controller_Router_Route('/admin/performer/:id_model/:name/banners/delete/:id_banner', array('controller' => 'banner', 'action' => 'delete-banners')))

         // theme actions
         ->addRoute("theme-profile",             new Zend_Controller_Router_Route('/my/:profile_type/*', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute("theme-watch",               new Zend_Controller_Router_Route('/live-broadcast', array('controller' => 'index', 'action' => 'watchModel')))
         ->addRoute("special-requests",    new Zend_Controller_Router_Route('/performer/:id_model/:name/special-requests', array('controller' => 'index', 'action' => 'modelProfile', "id_model" => null, 'name' => null, 'profile_type' => 'special-requests')))
         ->addRoute("theme-special-requests",    new Zend_Controller_Router_Route('/user/requests/:profile_type', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute("theme-special-request",     new Zend_Controller_Router_Route('/my/:profile_type/:id_special_request', array('controller' => 'index', 'action' => 'modelProfile')))
         ->addRoute("theme-blog",                new Zend_Controller_Router_Route('/my/:profile_type/post/:id_post/:title/:purchase', array('controller' => 'index', 'action' => 'modelProfile', 'purchase' => null)))
         ->addRoute('wall-model',         new Zend_Controller_Router_Route('/wall',array('controller' => 'index','action' => 'wall')))
         ->addRoute('wall-model-page',         new Zend_Controller_Router_Route('/wall[/page/:page]', array('controller' => 'index', 'action' => 'wall', 'page' => '1'), array('page' => '\d+')))

         //->addRoute("theme-video",   new Zend_Controller_Router_Route('/video/:id/:title', array('controller' => 'index', 'action' => 'video')))

        //zf2 route redirects

        ->addRoute('zf2-login', new Zend_Controller_Router_Route('/account/login', array('controller' => 'index', 'action' => 'redirectToLogin')));

        return $this->router;
    }

    protected function _initLastActivity(){
        //creaza variabila de sesiune daca nu exista
        if(!isset($_SESSION["lastActivity"])) @$_SESSION["lastActivity"] = time();

        if( (time() - @$_SESSION["lastActivity"]) > 10 ) {
            //daca timpul intermediar a expirat scrie in db
            @$_SESSION["lastActivity"] = time();

            switch(@$_SESSION['group']) {

                /*case 'model':
                    Zend_Loader::loadFile(APPLICATION_PATH."/models/model.php");
                    $visitor = new Model();
                break;

                case 'user':
                    Zend_Loader::loadFile(APPLICATION_PATH."/models/user.php");
                    $visitor = new User();
                break;

                case 'moderator':
                    Zend_Loader::loadFile(APPLICATION_PATH."/models/moderator.php");
                    $visitor = new Moderator();
                break;*/

                case  'default':
                    Zend_Loader::loadFile(APPLICATION_PATH . "/models/user.php", null, true);
                    $visitor = new User();
                break;
            }

            if(isset($visitor)) $visitor->update(array('last_activity' => time()), db()->quoteInto('id = ?',
	            @$_SESSION['user']['id']));

        }

    }

    protected function _initAcl(){
        $identity = Zend_Auth::getInstance()->getIdentity();
        $_SESSION['user']['id'] = $identity;
        $acl = new Zend_Acl();

        $acl->addRole(new Zend_Acl_Role('user'))
            ->addRole(new Zend_Acl_Role('model'))
            ->addRole(new Zend_Acl_Role('performer')) //zf2
            ->addRole(new Zend_Acl_Role('super_admin')) //zf2
            ->addRole(new Zend_Acl_Role('admin')) //zf2
            ->addRole(new Zend_Acl_Role('moderator'))
            ->addRole(new Zend_Acl_Role('theme-website'));

        $actions_model = array("shop",
                               "bank",
                               "request-from-admin",
                               "comments",
                               "member-standings",
                               'model-media-upload',
                               'model-manage-videos',
                               'model-manage-photos',
                               'model-media-record',
                               'model-privacy-settings',
                               'broadcast-quality'
                               );

        $actions_moderator = array(
                                       "chat-settings",
                                       "add-announcements",
                                       "_line_break_00",

                                       "models",
                                       "model-rates",
                                       "model-settings",
                                       "model-account-settings",
                                       "model-permissions",
                                       "model-payments-info",
                                       "model-blog-moderate",
                                       "model-pledges-moderate",
                                       "manage-model-chips",
                                       "_line_break_01",

                                       "moderators",
                                       "moderator-settings",
                                       "moderator-account-settings",
                                       "_line_break_02",

                                       "users",
                                       "user-account-settings",
                                       "user-settings",
                                       "manage-user-chips",

                                       "mod-timezone",
                                       "revenue-stats",
                                       "system-settings",
                                       "max_allowed_time",
                                       "gift_office_address",

                                       "moderate-reviews",
                                       "max_group_users",
                                       "manage-shops",
                                   );

         $website_model = array(
                                   "theme-index",
                                   "theme-filtermodels",
                                   "theme-vod",
                                   "theme-live",
                                   "theme-watchmodel",

                                   "theme-userprofile-profile",
                                   "theme-userprofile-favorite",
                                   "theme-userprofile-following",
                                   "theme-userprofile-friends",
                                   "theme-userprofile-edit",
                                   "theme-userprofile-specialrequests",
                                   "theme-userprofile-videos",
                                   "theme-userprofile-chips",

                                   "theme-messages",
                                   "theme-purchasechips",
                                   "theme-video",
                                   "theme-contact",
                                   "theme-login",
                                   "theme-logout",
                                   "theme-signup",
                                   "theme-verify",
                                   "theme-pwreset",

                                   "theme-modelprofile",
                                   "theme-modelprofile-videos",
                                   "theme-modelprofile-pictures",
                                   "theme-modelprofile-profile",
                                   "theme-modelprofile-wall",
                                   "theme-modelprofile-offers",
                                   "theme-modelprofile-blog",
                                   "theme-modelprofile-schedule",
                                   "theme-modelprofile-specialrequest",
                                   "theme-modelprofile-specialrequests",
                                   "theme-modelprofile-pledges",

                                   "theme-gallery",
                                   "theme-image",
                                   "theme-premiers",
                                   "theme-store",
                                   "theme-presentations",
                                   "theme-play",
                                   "theme-lobby",
                                   "theme-halloffame",
                                   "theme-recentphotos",
                                   "theme-mostpopularroom",
                                   "theme-groupshows",
                                   "theme-clips",
                                   "theme-page",
                                   "theme-models",

         );


        $user_permissions = array("model" => $actions_model,
                                  "moderator" => $actions_moderator
                                 );

        Zend_Registry::set("user_permissions", $user_permissions);

        //Add resources
        $acl->addResource(new Zend_Acl_Resource("all_resources"));

        foreach($actions_model as $action){
            $acl->addResource(new Zend_Acl_Resource($action) ,"all_resources");
        }

        foreach($actions_moderator as $action){
            $acl->addResource(new Zend_Acl_Resource($action) ,"all_resources");
        }

        //theme actions deny/allow users on model website
         foreach($website_model as $wm){
            $acl->addResource(new Zend_Acl_Resource($wm) );
            $acl->allow('theme-website', $wm, array('view'));
        }
        $acl->addResource(new Zend_Acl_Resource("moderator-permissions"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("model-notes"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("user-notes"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("moderator-notes"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("system-logs"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("static-pages"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("development-pages"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("rules"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("status-announcements"),"all_resources");
        $acl->addResource(new Zend_Acl_Resource("email-templates"),"all_resources");



        $acl->allow('admin','development-pages');

        /*
        model permission
        $acl->allow("model", array("lobby", "broadcast"), array("view", "edit"));
        */

        Zend_Registry::set("acl", $acl);

        return $acl;

    }

    public function run()
    {

        // this should fallback to zf1 when running zf1 old crons
        if (is_null($this->getEventManager())) {
            return parent::parentRun(true);
        }

        $front = $this->getResource('frontcontroller');
        $front->setParam('bootstrap', $this);

        $application = $this->getResource('zf2')->getApplication();
        $config = $application->getServiceManager()->get('Config');

        if ($config['zf2_for_1']['silent_zf1_fallback'] === true) {
            $this->getEventManager()->attach('zf1', array($this, 'parentRun'));
        }

        return false;

    }

    /**
     * @return mixed
     */
    public function getEventManager()
    {

        return $this->eventManager;
    }

    /**
     * @param $eventManager
     *
     * @return $this
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

}