<?php
return array(
    'OrgHeiglContact' => array(
        'mail_transport' => array(
//             'class'   => 'Zend\Mail\Transport\Smtp',
//             'options' => array(
//                 'host'             => 'localhost',
//                 //'port'             => 587,
//                 //'connectionClass'  => 'login',
//                 //'connectionConfig' => array(
//                 //    'ssl'      => 'tls',
//                 //    'username' => 'contact@your.tld',
//                 //    'password' => 'password',
//                 //),
//              ),
            'class' => 'File',
            'options' => array(
                'path' => sys_get_temp_dir(),
            ),
        ),
        'message' => array(/*
                 // These can be either a string, or an array of email => name pairs
        'to'     => 'contact@your.tld',
        'from'   => 'contact@your.tld',
        // This should be an array with minimally an "address" element, and
        // can also contain a "name" element
        'sender' => array(
                'address' => 'contact@your.tld'
        ),
        */
        ),
    ),
);