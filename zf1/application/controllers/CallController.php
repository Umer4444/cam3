<?php

class CallController extends App_Controller_Action
{

    /**
     * LAYOUT VARS
     *
     *
     */
    private $layout_control = null;
    private $route_name = null;
    private $params = array();

    public function init()
    {
        $this->params = $this->_request->getParams();

        $this->_data["route_name"] = $this->route_name = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        if (endsWith($this->route_name, "-frontend")) {
            $this->layout_control = "frontend";
        } elseif (endsWith($this->route_name, "-performer-backend")) {
            $this->layout_control = "performer";
        } elseif (endsWith($this->route_name, "-moderator-backend")) {
            $this->layout_control = "moderator";
        }

        $this->view->addHelperPath(APPLICATION_PATH . "/../library/App/View/Helper", "App_View_Helper");
        $this->view->setScriptPath(APPLICATION_PATH . "/../public/themes/default/views/scripts/");
        $this->_helper->layout->disableLayout();
    }

    public function makeCallAction()
    {

        /*
                        // Twilio REST API version
                        $version = "2010-04-01";

                        // Set our Account SID and AuthToken
                        $sid = config()->twilio->accountsid;
                        $token = config()->twilio->token;

                        // Instantiate a new Twilio Rest Client
                        $client = new Services_Twilio($sid, $token, $version);


                    $call = $client->account->calls->get( 'CAc64338da26f2a8b75fd930f0a9176fa2');
                    p($call->price_unit);
                exit; */

        if (!$this->params["id_model"]) $this->_redirect("/404");

        $this->load("model");
        $this->_data["model"] = $this->model->getById($this->params["id_model"]);

        if (!$this->_data["model"]->id) $this->_redirect("/404");

        if (!$this->_data["model"]->phone) {
            $this->_data["call"]["status"] = "fail";
            $this->_data["call"]["message"] = "Model has no phone number";
        } else {
            $post = $this->_request->getPost();
            if ($post && $post["callButton"]) {
                if ($post["phone"]) {
                    //validate phone number
                    //save phone to db
                    // add to user()->phone
                    // $_SESSION["user"]["phone"]
                    // echo "phone";
                    if (empty($post["phone"])) {
                        $this->_data["call"]["status"] = "fail";
                        $this->_data["call"]["message"] = "Enter your phone number";
                    } else {
                        $this->load("user");
                        $post["phone"] = trim(str_replace(array(" ", "-"), "", $post["phone"]));
                        $this->user->update(array("phone" => $post["phone"]), "id=" . (int)user()->id);

                        $_SESSION["user"]["phone"] = $post["phone"];
                        user()->phone = $post["phone"];
                    }

                }


                // Include the Twilio PHP library
                //require 'Services/Twilio.php';

                // Twilio REST API version
                $version = "2010-04-01";

                // Set our Account SID and AuthToken
                $sid = config()->twilio->accountsid;
                $token = config()->twilio->token;

                // A phone number you have previously validated with Twilio
                $phonenumber = '+19546213605'; // the number witch makes the call

                // Instantiate a new Twilio Rest Client
                $client = new Services_Twilio($sid, $token, $version);

                //$to = '+40741173215';   //user
                $to = user()->phone; //user

                try {
                    // Initiate a new outbound call
                    $call = $client->account->calls->create(
                        $phonenumber, // The number of the phone initiating the call
                        //str_replace(array("-", " "), "", $this->_data["model"]->phone), // The number of the phone receiving call
                        $to,
                        'http://89.137.90.223:8043/call/callback/id_model/' . $this->_data["model"]->id . "/id_user/" . user()->id . "/from_type/" . $_SESSION["group"], // The URL Twilio will request when the call is answered
                        array(
                            "StatusCallback" => 'http://89.137.90.223:8043/call/statuscallback/',
                        )
                    );
                    //echo 'Started call: ' . $call->sid;
                    $this->_data["call"]["status"] = "success";
                    $this->_data["call"]["message"] = $call->sid;
                } catch (Exception $e) {
                    //p($e->getStatus());
                    //echo 'Error: ' . $e->getMessage();
                    $this->_data["call"]["status"] = "fail";
                    $this->_data["call"]["message"] = $e->getStatus() . ' ' . $e->getMessage();

                }
            }

        }
        //exit;

        $this->_helper->layout->disableLayout();
        // send data to view, load view
        $this->view->assign($this->_data);
        $this->render("/make-call");
    }

    public function makeBrowserCallAction()
    {
        //p($this->params,1);
        if (!$this->params["id_model"]) $this->_redirect("/404");

        $this->load("model");
        $this->_data["model"] = $this->model->getById($this->params["id_model"]);

        /*
              // XML-related routine
                $xml = new DOMDocument('1.0', 'utf-8');
                $xml->appendChild($xml->createElement('foo', 'bar'));
                $output = $xml->saveXML();

                // Both layout and view renderer should be disabled
                Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
                Zend_Layout::getMvcInstance()->disableLayout();

                // Set up headers and body
                $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')
                    ->setBody($output);  */

        // send data to view, load view
        $this->view->assign($this->_data);
        $this->render("/make-browser-call");
    }

    public function callbackAction()
    {

        // mail("razvan.moldovan@perfectweb.ro","tesdfs 2 post back call", print_r($this->params,1));
        //  exit;
        header("content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo '<Response>';


        if (!$this->params["id_model"]) {
            echo "    <Say>101 Invalid performer.</Say>";
        } else {
            $this->load("model");
            $model = $this->model->getById($this->params["id_model"]);
            $this->load("user");
            $user = $this->user->getUserById($this->params["id_user"]);

            if ($model->phone) {
                if ($user->chips < (config()->call_cost * 5)) {
                    echo "<Say>103 Not enough chips. Minimum required is " . config()->call_cost * 5 . " for 5 minute call. Purchase chips and call again</Say>";
                } else {
                    $this->load("call_log");
                    $this->call_log->addCall(array(
                        "account_sid" => $this->params["AccountSid"],
                        "call_sid" => $this->params["CallSid"],
                        "from" => $user->display_name ? $user->display_name : $user->username,
                        "to" => $model->screen_name,
                        "status" => $this->params["CallStatus"],
                        "id_from" => $user->id,
                        "from_type" => $this->params["from_type"],
                        "id_to" => $model->id,
                        "to_type" => "model",
                        "start_date" => time(),
                    ));
                    echo "    <Say>Caling " . $model->screen_name . "</Say>";
                    echo '    <Dial callerId="1-954-621-3605">';
                    echo "    <Number>" . str_replace(array("-", " ", "(", ")"), "", $model->phone) . "</Number>";
                    echo '    </Dial>';
                }
            } else {
                echo "    <Say>102 Performer has no phone number defined</Say>";
            }
        }

        echo "</Response>";

        exit;
    }

    public function statuscallbackAction()
    {

        if ($this->params["CallSid"]) {
            $this->load("call_log");
            $this->call_log->updateCall(array("status" => $this->params["CallStatus"], "duration" => $this->params["CallDuration"], "end" => time()), $this->params["CallSid"]);
        }

        exit;
    }
}

