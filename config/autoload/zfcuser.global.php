<?php

$settings = array(

    /**
     * This is added by me from the zfc user doctrine module
     */
    'enable_default_entities' => false,
    /**
     * till here
     */

    'user_entity_class' => \Application\Entity\User::class,

    /**
     * Enable registration
     *
     * Allows users to register through the website.
     *
     * Accepted values: boolean true or false
     */
    'enable_registration' => true,

    /**
     * Enable Username
     *
     * Enables username field on the registration form, and allows users to log
     * in using their username OR email address. Default is false.
     *
     * Accepted values: boolean true or false
     */
    'enable_username' => true,

    /**
     * Authentication Adapters
     *
     * Specify the adapters that will be used to try and authenticate the user
     *
     * Default value: array containing 'ZfcUser\Authentication\Adapter\Db' with priority 100
     * Accepted values: array containing services that implement 'ZfcUser\Authentication\Adapter\ChainableAdapter'
     */
    'auth_adapters' => array( 100 => 'ZfcUser\Authentication\Adapter\Db' ),

    /**
     * Enable Display Name
     *
     * Enables a display name field on the registration form, which is persisted
     * in the database. Default value is false.
     *
     * Accepted values: boolean true or false
     */
    'enable_display_name' => false,

    /**
     * Modes for authentication identity match
     *
     * Specify the allowable identity modes, in the order they should be
     * checked by the Authentication plugin.
     *
     * Default value: array containing 'email'
     * Accepted values: array containing one or more of: email, username
     */
    'auth_identity_fields' => array( 'email', 'username' ),

    /**
     * Login form timeout
     *
     * Specify the timeout for the CSRF security field of the login form
     * in seconds. Default value is 300 seconds.
     *
     * Accepted values: positive int value
     */
    'login_form_timeout' => 300,

    /**
     * Registration form timeout
     *
     * Specify the timeout for the CSRF security field of the registration form
     * in seconds. Default value is 300 seconds.
     *
     * Accepted values: positive int value
     */
    'user_form_timeout' => 300,

    /**
     * Login After Registration
     *
     * Automatically logs the user in after they successfully register. Default
     * value is false.
     *
     * Accepted values: boolean true or false
     */
    'login_after_registration' => true,

    /**
     * Registration Form Captcha
     *
     * Determines if a captcha should be utilized on the user registration form.
     * Default value is false.
     */
    'use_registration_form_captcha' => false,

    /**
     * Form Captcha Options
     *
     * Currently used for the registration form, but re-usable anywhere. Use
     * this to configure which Zend\Captcha adapter to use, and the options to
     * pass to it. The default uses the Figlet captcha.
     */
    'form_captcha_options' => array(
        'class'   => 'figlet',
        'options' => array(
            'wordLen'    => 5,
            'expiration' => 300,
            'timeout'    => 300,
        ),
    ),

    /**
     * Use Redirect Parameter If Present
     *
     * Upon successful authentication, check for a 'redirect' POST or GET parameter
     *
     * Accepted values: boolean true or false
     */
    'use_redirect_parameter_if_present' => true,

    /**
     * Sets the view template for the user login widget
     *
     * Default value: 'zfc-user/user/login.phtml'
     * Accepted values: string path to a view script
     */
    'user_login_widget_view_template' => 'zfc-user/user/login.phtml',

    /**
     * Login Redirect Route
     *
     * Upon successful login the user will be redirected to the entered route
     *
     * Default value: 'zfcuser'
     * Accepted values: A valid route name within your application
     *
     */
    'login_redirect_route' => 'home',

    /**
     * Logout Redirect Route
     *
     * Upon logging out the user will be redirected to the enterd route
     *
     * Default value: 'zfcuser/login'
     * Accepted values: A valid route name within your application
     */
    'logout_redirect_route' => 'home',

    /**
     * Password Cost
     *
     * The number represents the base-2 logarithm of the iteration count used for
     * hashing. Default is 14 (about 10 hashes per second on an i5).
     *
     * Accepted values: integer between 4 and 31
     */
    'password_cost' => 14,

    /**
     * Enable user state usage
     *
     * Should user's state be used in the registration/login process?
     */
    'enable_user_state' => true,

    /**
     * Default user state upon registration
     *
     * What state user should have upon registration?
     * Allowed value type: integer
     */
    'default_user_state' => \PerfectWeb\Core\Utils\Status::ACTIVE,

    /**
     * States which are allowing user to login
     *
     * When user tries to login, is his/her state one of the following?
     * Include null if you want user's with no state to login as well.
     * Allowed value types: null and integer
     */
    'allowed_login_states' => array(\PerfectWeb\Core\Utils\Status::ACTIVE),

    /**
     * User table name
     */
    'table_name' => 'user',

);

return array(
    'zfcuser' => $settings,
    'service_manager' => array(
        'aliases' => array(
            'zfcuser_zend_db_adapter' =>
                (isset($settings['zend_db_adapter'])) ? $settings['zend_db_adapter'] : 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'zfcuserimpersonate' => array(
        /**
         * User Id Route Parameter
         *
         * The name of the route parameter that specifies the user id, passed as part of the impersonate route
         *
         * Accepted values: string
         */
        //'user_id_route_parameter'      => 'userId',

        'impersonate_redirect_route' => 'zfcadmin',
        'unimpersonate_redirect_route' => 'zfcadmin',
        'store_user_as_object' => true,
    )
);
