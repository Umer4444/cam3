<?php

/**
 *
 *
 * Default config for test payment gateway:
 *
 */
class App_Payment_Gateway_Epoch extends App_Payment_Gateway_Base {

    public function getFormData() {

        $this->load("chips_packages");
        $plan = $this->chips_packages->findById($this->session->plan);
        if($plan) {
            //$code = $plan->epoch_form;
        } else {
            $code = null;
        }

 /*       $data = parent::getFormData();

        $data["auth_amount"]        = $this->session->amount;
        $data["pburl"]              = "http://xexposed.com/payment/gateway/type/epoch";
        $data["returnurl"]          = "http://xexposed.com/payment/success/type/epoch";
        $data["member_id"]          = $this->session->member_id;
        ksort($data);
        $sorted_string = urldecode(str_replace(array("=", "&"), '', http_build_query($data)));

        //$data["sort_string"] =  $sorted_string;
        //$data['key'] = config()->payments->getByType('epoch')->config->key;
        //$data['hmac'] = hash_hmac('md5',  urldecode($sorted_string),  config()->payments->getByType('epoch')->config->key);
        $data["epoch_digest"] =  hash_hmac('md5', $sorted_string,  config()->payments->getByType('epoch')->config->key);
        */
        /* epoch purchase plus */
        $data2["api"] = "join";

        //get user settings and chef if already registered
        $this->load("user_settings");
        $user_settings = $this->user_settings->getUserFieldById($this->session->id_user, "user");

        if(array_key_exists("epoch_member_id", $user_settings) && array_key_exists("epoch_username", $user_settings) && array_key_exists("epoch_pi_code", $user_settings)){
            $data2["api"] = "memberplus";
            $data2["member_id"] = $user_settings["epoch_member_id"];
            $data2["username"] = $user_settings["epoch_username"];
            $data2["referrer_pi_code "] = $user_settings["epoch_pi_code"];
        }

        $data2["pi_code"] = 'epoch_form';//$code;

        $data2["reseller"] = "a";

        $data2["x_plan"] = $this->session->plan;
        $data2["x_id"] = $this->session->id;
        $data2["x_uid"] = $this->session->id_user;

        $data2["x_sid"] = session_id();

      //  $data2["pi_returnurl"]              = "http://xexposed.com/payment/gateway/type/epoch";
        // p($data2,1);
        return $data2;
    }

    protected function getStatusUrlParam() {
        return "STATUS_URL";
    }

    protected function getSuccessUrlParam() {
        return "SUCCESS_URL";
    }

    protected function getFailedUrlParam() {
        return "ERROR_URL";
    }

    protected function validate(Zend_Controller_Request_Http $request) {
        // just simple verification here
        if ($request->getParam("x_id") != $this->session->id)
            throw new App_Payment_Exception("Invalid payment id");
    }

    public function savePayment(Zend_Controller_Request_Http $request, $payment){

        $this->load('payment_method');
        $id_method = $this->payment_method->getMethodByType($payment->getType());
        //$chips = config()->chips_parity * $payment->getAmount();
        $chips = $payment->getChips();
        $paymentArray= array(
            'added'             => time(),
            'id_user'           => ($payment->getIdUser()),
            'user_type'         => $payment->getUserType(),
            'id_order'          => $request->getPost('order_id'),
            'start_date'        => date("Y m d"),
            'end_date'          => date("Y m d"),
            'id_pachet'         => $payment->getPlan(),
            'amount'            => $payment->getAmount(),
            'currency'          => 'USD',
            'chips'             => $chips,
            'payment_method'    => $id_method,
            'payment_type'      => 'buy', //enum('buy','pay','refund')
            'status'            => $payment->getStatus(),
         );

        $this->load('payments');
        $insert = $this->payments->insert($paymentArray);

        $this->load('user');
        $this->user->update( array('chips' =>  new Zend_DB_Expr('chips + '.  $chips )), 'id ='.$payment->getIdUser() );

    }
}