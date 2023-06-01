<?

class App_View_Helper_Theme extends Zend_View_Helper_Abstract{
    
    protected $_theme = "default";
        
    function theme($file = ''){
        if(!empty($file)) {
            if(file_exists(APPLICATION_PATH."/../public/themes/".$this->_theme.'/'.$file))
                return "/themes/".$this->_theme."/".$file;
            else
                return "/themes/default/".$file;
                
        }
        return $this;
    }
    
    function setTheme($theme){
        $this->_theme = $theme;
        if($theme != "default") $this->view->addBasePath(APPLICATION_PATH."/../public/themes/".$theme."/views");
    }
    
     function getTheme($theme){
        return $this->_theme;
    }
    
    
    
}