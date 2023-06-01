<?
class Blog_purchase extends App_Model{
    
    protected $_name = "blog_purchase";
    
    //protected $_primary = "id"; 

    public function purchasePost($id_post = false){        
        if(!$id_post) return false;
       
        $this->delete(
            new Zend_Db_Expr("id_user=".(int)$_SESSION["user"]["id"]." AND user_type = '".$_SESSION["group"]."' AND id_post=".(int)$id_post)
        );                        
        return $this->insert(array(
                    "id_user" => (int)$_SESSION["user"]["id"], 
                    "user_type" => $_SESSION["group"], 
                    "id_post" => (int)$id_post)
         ); 

    }
}    