<?

class App_Log extends Zend_Log{
    
    public function registerErrorHandler(){
        
        // Only register once.  Avoids loop issues if it gets registered twice.
        if ($this->_registeredErrorHandler) {
            return $this;
        }

        $this->_origErrorHandler = set_error_handler(array($this, 'errorHandler'),E_ALL ^ E_NOTICE);

        // Contruct a default map of phpErrors to Zend_Log priorities.
        // Some of the errors are uncatchable, but are included for completeness
        $this->_errorHandlerMap = array(
            E_NOTICE            => Zend_Log::NOTICE,
            E_USER_NOTICE       => Zend_Log::NOTICE,
            E_WARNING           => Zend_Log::WARN,
            E_CORE_WARNING      => Zend_Log::WARN,
            E_USER_WARNING      => Zend_Log::WARN,
            E_ERROR             => Zend_Log::ERR,
            E_USER_ERROR        => Zend_Log::ERR,
            E_CORE_ERROR        => Zend_Log::ERR,
            E_RECOVERABLE_ERROR => Zend_Log::ERR,
            E_STRICT            => Zend_Log::DEBUG,
        );
        // PHP 5.3.0+
        if (defined('E_DEPRECATED')) {
            $this->_errorHandlerMap['E_DEPRECATED'] = Zend_Log::DEBUG;
        }
        if (defined('E_USER_DEPRECATED')) {
            $this->_errorHandlerMap['E_USER_DEPRECATED'] = Zend_Log::DEBUG;
        }

        $this->_registeredErrorHandler = true;
        return $this;
    }
    
}