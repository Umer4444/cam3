<?php

namespace PerfectWeb\Payment\Form;

use Zend\Form\Form;

class Unlock extends Form
 {

     public function __construct($name = 'unlock')
     {

         parent::__construct('album');

         $this->add(array(
             'name' => 'password',
             'type' => 'Password',
             'options' => array(
                 'label' => 'Password',
             ),
         ));

         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Unlock',
             ),
         ));

     }

}