<?php

/**
 * 
 * 
 * Default config for test payment gateway:
 * 
 */
class App_Payment_Gateway_Dwolla extends App_Payment_Gateway_Base {
    
    public function getFormData() {
        

        $key    =  config()->payments->getByType('dwolla')->config->key;
        $secret =  config()->payments->getByType('dwolla')->config->secret;
         
        $data = parent::getFormData();
        
        
       
        $data["key"] = $key;
        $data["orderId"] = $this->session->id;
        $data["timestamp"] = time();
        $data["amount"] = $this->session->amount;
        $data["signature"] = hash_hmac('sha1', "{$data["key"]}&{$data["timestamp"]}&{$data["orderId"]}", $secret);
        
        return $data;
    }
    
    protected function getStatusUrlParam() {
        /**
        * URL to POST the transaction response to after the user authorizes the purchase. If not provided, will default to the Payment Callback URL set for the consumer application. If no default found, results in error.
        */
        return "callback";
    }

    protected function getSuccessUrlParam() {
        /**
        * URL to return the user to after they authorize or cancel the purchase. If not provided, will default to the Payment Redirect URL set for the consumer application. If no default found, results in error.
        */
        return "redirect";
    }

    protected function getFailedUrlParam() {
        return "ERROR_URL";
    }
    
    protected function validate(Zend_Controller_Request_Http $request) {

        // just simple verification here
        if ($request->getParam("pid") != $this->session->id) 
            throw new App_Payment_Exception("Invalid payment id");
    }
    
}

