<?php

/**
 * 
 * 
 * Default config for test payment gateway:
 * 
 */
class App_Payment_Gateway_Paxum extends App_Payment_Gateway_Base {
    
     
    public function getConfig() {
        return parent::getConfig();
    }    
    
    public function getFormData() {
        $data = parent::getFormData();
        

//        $this->load("payments_info");
//        $methods = $this->payments_info->getPaymentsMethodsByUserId($this->session->to_id_model, 'model');

//        $payments =array();
//        if($methods){
//            foreach($methods as $method){
//                $decoded = unserialize($method->info);
//                foreach($decoded as $k=>$v){
//                     if($method->name == "Paxum")
//                        $payments[$method->name] = $v  ; 
//                     break;  
//                }                       
//                                
//            }
//        } 
        
        
        $this->load("chips_packages");
        $plan = $this->chips_packages->findById($this->session->plan);
        if($plan) {
            $product = $plan->name;           
        } else {
            $product = "Chips package purchase";
        }

        
        $data["business_email"] = config()->payments->getByType('paxum')->getConfig()->form->business_email;  
        $data["button_type_id"] = "1"; //1-pay, 2- subscribe    
        $data["item_name"] = $product;   
        $data["amount"] = $this->session->amount;    
        $data["variables"] =  "notify_url=http://".$_SERVER["SERVER_NAME"]."/payment/success/type/paxum&x_sid=".session_id()."&x_id=".$this->session ->id;
        return $data;
        
    }
    
    protected function getStatusUrlParam() {
        return "notify_url";
    }

    protected function getSuccessUrlParam() {
        return "finish_url";
    }

    protected function getFailedUrlParam() {
        return "cancel_url";
    }
     

    
    protected function validate(Zend_Controller_Request_Http $request) {
        // just simple verification here
        if ($request->getParam("pid") != $this->session->id) 
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