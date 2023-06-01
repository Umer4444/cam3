<?
class Blog_access extends App_Model{
    
    protected $_name = "blog_access";
    
    protected $_primary = "id"; 
    
    public function getByPostId($id_post = null){
        if(!$id_post) return null;
        
        $select = $this->select()->where("id_post=?", $id_post);
        
        return $this->fetchAll($select);
        
    }
    
    public function saveAccess($id_post = null, $post = null){
       
        if(!$id_post || !$post || !is_array($post)) return false;
      //    p($post);
/*         $sql = "INSERT INTO blog_access (id_post, type, date, chips) VALUES ";            
                            
        $sql .= "({$id_post}, 'vip',        '".(!empty($post["vip_date"])        ? strtotime($post["vip_date"])         : "")."', '".$post["vip_chips"]."'),";
        $sql .= "({$id_post}, 'members',    '".(!empty($post["members_date"])    ? strtotime($post["members_date"])     : "")."', '".$post["members_chips"]."'),";
        $sql .= "({$id_post}, 'performers', '".(!empty($post["performers_date"]) ? strtotime($post["performers_date"])  : "")."', '".$post["performers_chips"]."'),";
        $sql .= "({$id_post}, 'everyone', '".(!empty($post["everyone_date"]) ? strtotime($post["everyone_date"])  : "")."', '".$post["everyone_chips"]."'),";
        $sql .= "({$id_post}, 'fan',        '".(!empty($post["fan_date"])        ? strtotime($post["fan_date"])         : "")."', '".$post["fan_chips"]."'),";
        $sql .= "({$id_post}, 'public',     '".(!empty($post["public_date"])     ? strtotime($post["public_date"])      : "")."', '0')";
        //       db()->query($sql);*/
        $array  = array(
                    array("id_post" => $id_post, "type" => "vip",       "date" => (!empty($post["vip_date"])        ? strtotime($post["vip_date"])        : time()), "chips" => ($post["vip_chips"]        ? $post["vip_chips"] : 0)),
                    array("id_post" => $id_post, "type" => "members",   "date" => (!empty($post["members_date"])    ? strtotime($post["members_date"])    : time()), "chips" => ($post["members_chips"]    ? $post["members_chips"] : 0)),
                    array("id_post" => $id_post, "type" => "everyone",  "date" => (!empty($post["everyone_date"])   ? strtotime($post["everyone_date"])   : time()), "chips" => ($post["everyone_chips"]   ? $post["everyone_chips"] : 0)),
                    array("id_post" => $id_post, "type" => "performers","date" => (!empty($post["performers_date"]) ? strtotime($post["performers_date"]) : time()), "chips" => ($post["performers_chips"] ? $post["everyone_chips"] : 0)),
                    array("id_post" => $id_post, "type" => "fan",       "date" => (!empty($post["fan_date"])        ? strtotime($post["fan_date"])        : time()), "chips" => ($post["fan_chips"]        ? $post["fan_chips"] : 0)),
                    array("id_post" => $id_post, "type" => "public",    "date" => (!empty($post["public_date"])     ? strtotime($post["public_date"])     : time()), "chips" => ($post["public_chips"]     ? $post["public_chips"] : 0)),
                );
   
        foreach($array as $arr)        
            $this->insert($arr);
  
    }
    

}    