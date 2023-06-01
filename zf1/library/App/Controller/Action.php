<?

class App_Controller_Action extends Zend_Controller_Action{

	public $_data = array(); // init all the vars u want in here

    protected $_params = array(); // controller params

    protected $em = null; //doctrine entity manager

	function init(){

		$this->liteInit();

		$this->log = new Zend_Log();

		$this->acl = $this->view->acl = $this->getInvokeArg("bootstrap")->getResource("acl");
        $this->_helper->layout->disableLayout();


        //$this->load("model_websites");
       // $website = $this->model_websites->getWebsite();

        //$_SESSION["website"]["id"]              = ($website->id ? $website->id : 0)  ;
        //$_SESSION["website"]["id_model"]        = ($website->id_model ? $website->id_model : null)  ;
        //$_SESSION["website"]["screen_name"]     = ($website->screen_name ? $website->screen_name : null)  ;
        //$_SESSION["website"]["denied_actions"]  = ($website->denied_actions ? $website->denied_actions : null)  ;


		$this->setupAcl();

		// assign all params to view in case we need them
		$this->params = $this->view->params=$this->_params=$this->_getAllParams();
		$this->view->request = $this->request = $this->getRequest();

		// There are some pages that dont need to check for login, so dont
		//if($this->_params['controller']=="admin" && $this->_params['action']!="login" && $this->_params['action']!="forgot" && $this->_params['action']!="logout" && $this->_params['action']!="secure") Auth::checkUser();


        //$th = ($website->url ? str_replace(".", "_", $website->url) : "default");
        $th = "default";
        $this->_helper->layout->disableLayout();
        $this->setTheme($th);

		$this->_data['notice'] = current($this->_helper->FlashMessenger->getMessages());

        $this->em = Zend_Registry::get('service_manager')->get('Doctrine\ORM\EntityManager');

	}

	function liteInit(){

		$this->view->db = $this->db = $this->getInvokeArg("bootstrap")->getResource("db");
		$this->dbCache = $this->getInvokeArg("bootstrap")->getResource("dbCache");

	}

    function setTheme($theme = "default"){

        $this->view->setBasePath(APPLICATION_PATH."/../public/themes/default/views");
        $this->view->addHelperPath(APPLICATION_PATH."/../library/App/View/Helper", "App_View_Helper");

        $this->view->addScriptPath(APPLICATION_PATH."/../public/themes/default/views");

        switch($this->_params['controller']){

            case "process":
                $this->_helper->layout->disableLayout();
                break;
            case "model":
            case "admin":
            case "banner":
            case "blog":
            case "pledge":

            default:
                //$this->_helper->layout->setLayoutPath(APPLICATION_PATH."/layouts")
                //                      ->setLayout("layout");
                if(file_exists(APPLICATION_PATH."/../public/themes/".$theme."/views/scripts/layouts/layout.phtml")){
                    $this->_helper->layout->setLayoutPath(APPLICATION_PATH."/../public/themes/".$theme."/views/scripts/layouts")
                                      ->setLayout("layout");
                } else {
                    $this->_helper->layout->setLayoutPath(APPLICATION_PATH."/../public/themes/default/views/scripts/layouts")
                                      ->setLayout("layout");
                }
            break;

        }

    }

    /**
    * Incarca un model
    *
    * @param mixed $model
    * @return {@link $model}
    */
	public function load($model){

		if(!isset($this->{$model})){

			$modelUrl=APPLICATION_PATH."/models/".$model.".php";

			if(!file_exists($modelUrl) && !file_exists($defaultModelUrl)) throw new Zend_Exception("The requested model does not exist in <u>".$modelUrl."</u>");

			Zend_Loader::loadFile($modelUrl,null,true);

			$this->{$model} = new $model;

		}

		return $this->{$model};

	}

    function postDispatch(){

        $this->view->assign($this->_data);
    }

	private function setupAcl(){

		 $acl = Zend_Registry::get('acl');


        if(isset($_SESSION["website"]["id"] ) && $_SESSION["website"]["id"]  != 0){
            $actions_denied = explode(",", $_SESSION["website"]["denied_actions"]);
           // p($acl,1);
           // p($actions_denied,1);
            foreach($actions_denied as $ad) {
                $acl->deny('theme-website', trim(strtolower($ad)), 'view');
            }
        }

        if($_SESSION['group'] == "moderator" || $_SESSION['group'] == "model"){

		    $this->load("permissions");

		    $perms = $this->permissions->getPermissionsForUser(user()->id, $_SESSION['group']);
            if($perms) $perms = $perms->toArray();
            else $perms = array();

            if(count($perms) > 0){



                foreach($perms as $perm)
                {
                    if($perm['action'] == 'all' && $perm['type']){//for superadmin
                   //     $actions = user_permissions();
//                        $actions = $actions['moderator'];
//
//                        foreach($actions as $action){
//                            $acl->allow($perm['type'], $action, explode(",", $perm['permission']));
//
//                        }
//                        $acl->allow('moderator', 'moderator-permissions', array('view', 'edit'));
//                        $acl->allow('moderator', 'model-notes', array('view', 'edit'));
//                        $acl->allow('moderator', 'user-notes', array('view', 'edit'));
//                        $acl->allow('moderator', 'moderator-notes', array('view', 'edit'));
//                        $acl->allow('moderator', 'system-logs', array('view', 'edit'));
//                        $acl->allow('moderator', 'static-pages', array('view', 'edit'));
//                        $acl->allow('moderator', 'development-pages', array('view', 'edit'));
//                        $acl->allow('moderator', 'rules', array('view', 'edit'));
//                        $acl->allow('moderator', "mod-timezone", array('view', 'edit'));
                        $acl->allow('moderator', "all_resources", array('view', 'edit'));



                    }
                    else{
                        //p($perm);
                        $acl->allow($perm['type'], $perm['action'], explode(",", $perm['permission']));
                    }
                }


                //p($acl);
                //exit;
            }

		}

       Zend_Registry::set("acl", $acl);
    }

    public function addNotification($notification = null, $type = null, $email = null){

        if(empty($notification) || !is_array($notification) || !$type) return false;

        if($type == "admin") $notification["id_to"] = 0;

        $this->load("user_notifications_mail");

        $this->load("user_notifications");
        $this->user_notifications->insert($notification);

        if($this->user_notifications_mail->getEmailPermission($notification["type_to"],$notification["id_to"],$notification["type"])){
             if($type=="model" || $type="performer") {
                    $this->load("model");
                    $email = $this->model->getNotificationEmail($notification["id_to"]);
             } elseif($type=="moderator" || $type == "admin") {
                    $this->load("moderator");
                    $email = $this->moderator->getNotificationEmail($notification["id_to"]);

             }
             $subject = "New notification " ; //.$type;
             $message = $notification["notification"];

            mail($email, $subject, $message);
            return;
        }
    }

    public function captcha(){
        $recaptchaKeys = config()->recaptcha;
        $recaptcha = new Zend_Service_ReCaptcha($recaptchaKeys->publickey, $recaptchaKeys->privatekey,
                NULL, array('theme' => 'red',  "lang" => 'en'));
        $recaptcha->setOptions(array(
                "lang" => 'en'
            ));
        $captcha = new Zend_Form_Element_Captcha('captcha',
            array(
                'label' => 'Type the characters you see in the picture below.',
                'captcha' =>  'ReCaptcha',
                'captchaOptions'        => array(
                    'captcha'   => 'ReCaptcha',
                    'service' => $recaptcha
                )
            )
        );

        return $captcha;
    }

    public function validateCaptcha($post){

        if(empty($post['recaptcha_challenge_field']) || empty($post['recaptcha_response_field'])) return false;

        $recaptchaKeys = config()->recaptcha;
        $recaptcha = new Zend_Service_ReCaptcha($recaptchaKeys->publickey, $recaptchaKeys->privatekey);
        $result = $recaptcha->verify(
                        $post['recaptcha_challenge_field'] ,
                        $post['recaptcha_response_field']
                    );

         return  $result->isValid();
    }

    public function redirectToLogin($type = "user", $redirectTo = "")
    {
/*        $controllers = array(
            'user' => 'index',
            'admin' => 'admin',
            'model' => 'model',
        );

        if (!isset($controllers[$type])) {
            $type = 'index';
        } else {
            $type = $controllers[$type];
        }

        $adminUrl = $this->view->url(array(
                'controller' => $type,
                'action' => 'index',
            ),
            null,
            true,
            false
        );

 */
        $baseUrl = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost();
        /*$url = trim($baseUrl . $adminUrl, "/") . "/";

        if ($redirectTo && $redirectTo != $url) {
            $url = $baseUrl . $redirectTo;
        }*/

        $redirectUrl = $this->view->url(
            array(
                'controller' => 'index',
                'action' => 'redirectToLogin',
            ),
            'zf2-login',
            false,
            true
        );

        $redirectUrl = $baseUrl . $redirectUrl . "?redirect=" . urlencode($redirectTo);

        $this->_redirect($redirectUrl);
    }
}