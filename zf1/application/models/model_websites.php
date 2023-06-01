<?

class Model_websites extends App_Model{
    
    protected $_name="model_websites";
    
    protected $_primary="id";
    
    public function getWebsite() {  
        $select = $this->select()->setIntegrityCheck(false)
                        ->from(array("mw" => $this->_name), array("id" => "id","url" => "url", "id_model" => "id_model", "title" => "title", "denied_actions" => "denied_actions"))
                        ->joinLeft(array("m" => "user"), "m.id=mw.id_model", array("screen_name"))
                        ->where(new Zend_Db_Expr("mw.url='".$_SERVER["HTTP_HOST"]."' OR mw.url='".$_SERVER["SERVER_NAME"]."'"))
                        ->limit(1);

        return $this->fetchRow($select);                                        
    }
    
    public function getArray(){
        
        $arr =$this->fetchAll($this->select())->toArray();
        $return_arr = array();
        $return_arr[0] = "All";
        foreach($arr as $row){
             $return_arr[$row["id"]] = $row["url"];
        }
        
        return $return_arr;
    }
    
    public function addWebsite($website = null){
        if(!is_array($website) || empty($website)) return false;
        
        $exists = $this->fetchRow($this->select()->where("id_model=?", $website["id_model"]));
        if($exists) {            
            $this->update($website, new Zend_Db_Expr("id=".$exists->id));
        }
        else
            $this->insert($website);
    }
}