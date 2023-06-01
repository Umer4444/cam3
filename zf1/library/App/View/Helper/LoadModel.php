<?

class App_View_Helper_LoadModel extends Zend_View_Helper_Abstract{

    protected $_theme = "default";

    function loadModel($file){
        //if(file_exists(APPLICATION_PATH."/models/".$file.".php")){
//            if(!function_exists($file)){
                //Zend_Registry::getInstance()
                $class = ucwords($file);

                if(!class_exists($class))
                    include_once(APPLICATION_PATH."/models/".$file.".php");

                if(!Zend_Registry::isRegistered("model:".$class))  {
                    Zend_Registry::set("model:".$class, new $class());
                }

                return Zend_Registry::get("model:".$class);
           // }
//        }
//        return;
                //return APPLICATION_PATH."/models/".$file;
    }

}