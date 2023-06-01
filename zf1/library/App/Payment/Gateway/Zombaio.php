<?php

/**
 *
 *
 * Default config for test payment gateway:
 *
 */
class App_Payment_Gateway_Zombaio extends App_Payment_Gateway_Base {

    public function getFormData() {
        $data = parent::getFormData();

        $data["Email"]              = user()->email;
        $data["identifier"]         = user()->id;
        $data["hide_credits"]       = 'True';
        $data["DynAmount_Value"]    = $this->session->amount;
        $data["DynAmount_Hash"]     = md5($this->config->gwpass.$this->session->amount);

        return $data;
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

        if ($request->getPost("pid") != $this->session->id)
            throw new App_Payment_Exception("Invalid payment id");
    }

    public function savePayment(Zend_Controller_Request_Http $request, $payment){

                    $this->load('payment_method');
                    $id_method = $this->payment_method->getMethodByType($payment->getType());
                    $chips = $payment->getChips();



                    $paymentArray= array(
                        'added'             => time(),
                        'id_user'           => ($payment->getIdUser()),
                        'user_type'         => $payment->getUserType(),
                        'id_order'          => $request->getPost('subscription_id'),
                        'start_date'        => $request->getPost('start_date'),
                        'end_date'          => $request->getPost('start_date'),
                        'id_pachet'         => 0,
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
                   $user = $this->user->getUserById($payment->getIdUser());

                    $this->load('user');
                    $this->user->update( array('chips' =>  new Zend_DB_Expr('chips + '. ($user->chips + $chips) )), 'id ='.$payment->getIdUser() );



    }

}