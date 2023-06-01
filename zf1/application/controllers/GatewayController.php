<?php

/**
 * payment gateway controller.
 *
 * @author l0co@wp.pl
 */
class GatewayController extends App_Controller_Payment_Action
{

    /** Just displays payment form **/
    public function indexAction()
    {

        if (!Auth::isLogged()) {
            $this->redirectToLogin('user', $this->getRequest()->getRequestUri());
        }

        $this->_data['payments'] = App_Payment_Registry::getInstance();
        $this->load("chips_packages");

        $this->_data["formAction"] = "/gateway";
        $this->_data["selected"] = "";
        $post = $this->request->getPost();

        if ($post) {
            $this->_data["selected"] = $post["method"];
            $this->_data["packages"] = $this->chips_packages->fetchAll($post["method"] == "epoch" ? "300" : $this->chips_packages->select());
            $this->_data["formAction"] = "/gateway/start";
        }

    }

    /** Receives data from 'test' action form and runs payments procedure **/
    public function startAction()
    {
        // WARN: no validation in this test method

        if ($this->_getParam('plan')) {
            $id_plan = (int)$this->_getParam('plan');
            $this->load("chips_packages");
            $plan = $this->chips_packages->findById($id_plan);
            if ($plan) {
                $amount = $plan->amount;
                $chips = $plan->amount;
            }

        } else {
            $amount = $this->_getParam('amount');
            $rate = config()->chips_parity;
            $chips = $amount * $rate;
            $id_plan = 0;
        }

        $description = $this->_getParam('description');
        $method = $this->_getParam('method');
        $id_user = $_SESSION['user']['id'];
        $user_type = $_SESSION['group'];
        //$to_id_model = $this->_getParam('toIdModel');
        $member_id = $this->_getParam('member_id');
        // $emailTo = $this->_getParam('emailPaxum');

        if (!$method) {
            echo "no method";
            exit;
        }
        $pid = App_Payment_Registry::getInstance()->getByType($method)->beginSession($amount, $description, $id_user, $user_type, md5(microtime()), $member_id, $chips, $rate, $id_plan);

        $this->_redirect("/payment/begin?pid=" . $pid);
    }

    /** Reacts on success payment **/
    public function successAction()
    {
        //$this->disableView();
        $this->_data['message'] = "Thank you for your support!<br>The transaction has been successfully completed.";
    }

    /** Reacts on failed payment **/
    public function errorAction()
    {
        $this->disableView();
        echo "Unfortunatelly your transaction failed!<br>Please try again.";
    }

}

