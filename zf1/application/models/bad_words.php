<?
class Bad_words extends App_Model{
    
    protected $_name = "bad_words";
    
    protected $_primary = "id"; 
    
    public function getAllArray(){
        
        $words = $this->fetchAll($this->select());
        
        $arr = array(
                "words_js" => "",
                "replacements_js" => "",
                "words" => array(),
                "replacements" => array()
            );
        foreach($words as $word){
            $arr["words_js"]         .= '"'.$word->word.'",';
            $arr["replacements_js"]  .= '"'.$word->replacement.'",';
            $arr["words"][]         = "/\b{$word->word}\b/i";//'/'.$word->word.'/i';
            $arr["replacements"][]  = $word->replacement;
        }
        
        $arr["words_js"]         = ''.trim($arr["words_js"],",")."";
        $arr["replacements_js"]  = ''.trim($arr["replacements_js"],",")."";
        
        return $arr;
        
    }
    
    public function deleteMultiple($notifications = null){        
        if(!$notifications) return false;
        $notifications = trim($notifications, ",");
        $this->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '".$notifications."') > 0"));
    }

    public function selectByModelId($modelId = null){

         $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("u" => "bad_words"), "words")
            ->where("model_id=?",$modelId);

        return $this->fetchRow($select)->words;

    }
}    