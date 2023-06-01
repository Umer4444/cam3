<?

class Purchased_content extends App_Model{
    
    protected $_name="purchased_content";
    
   
   public function addItem(array $content){
        
       if(!is_array($content)
            || !isset($content["type"])
            || !isset($content["id"])
        ) 
            return false;
       
       $where = db()->quoteInto('id_user = ?', $_SESSION["user"]["id"]);
       $where .= " AND " . db()->quoteInto('user_type = ?', $_SESSION["group"]);
       $where .= " AND " . db()->quoteInto('content_type = ?', $content["type"]);
       $where .= " AND " . db()->quoteInto('id_content = ?', $content["id"]);
       //$this->delete($where);
       
       $data = array(
                        "id_user"       => $_SESSION["user"]["id"],
                        "user_type"     => $_SESSION["group"],
                        "content_type"  => $content["type"]     ? $content["type"]  : "",
                        "id_content"    => $content["id"]       ? $content["id"]    : "",
                        "amount"        => $content["amount"]   ? $content["amount"]: "",
       );
       return $this->insert($data);
   }
}

