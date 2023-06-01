<?php

/**
 * Class ErrorController
 */
class ErrorController extends App_Controller_Action
{

    /**
     * @throws Zend_Controller_Response_Exception
     * @throws Zend_Exception
     */
    public function errorAction()
    {

        $errors = $this->_getParam('error_handler');

        if (!$errors) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

            // 404 error -- controller or action not found
            $this->getResponse()->setHttpResponseCode(404);
            $this->view->message = 'Pagina inexistenta';
            break;
        default:
            // application error
            $this->getResponse()->setHttpResponseCode(500);
            $this->view->message = 'Eroare de aplicatie';
            Zend_Registry::get("log")->crit("error controller: " . $errors->exception->getMessage().' '.$errors->exception->getTraceAsString());
            break;
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) $this->view->exception = $errors->exception;

        $this->view->request = $errors->request;

        if (APPLICATION_ENV == "production") $this->render("404");

    }

}

