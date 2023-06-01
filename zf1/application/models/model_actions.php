<?

class Model_actions extends App_Model
{

    protected $_name = "model_actions";

    protected $_primary = "action";


    public function updateType($type = null, $id = null)
    {
        if (!$type || !$id) return false;
        $this->update(array("status" => new Zend_Db_Expr(" status +1")), $this->getAdapter()->quoteInto("action=?", $type) . " AND " . $this->getAdapter()->quoteInto("id_model=?", $id));
    }

    public function actionAdd($type = null, $id = null)
    {
        if (!$type || !$id) return false;
        $this->insert(array("action" => $type, "id_model" => $id));
    }

    public function getAlerts()
    {
        $alerts = $this->fetchAll($this->select());
        $a = array();
        foreach ($alerts as $alert) {
            $a[$alert->action][] = $alert->id_model;
        }

        return $a;
    }
//    function getCategoriesByModel($id_model){
//        
//        $select = $this->select()
//                       ->setIntegrityCheck(false)
//                       ->from(array("c" => "categories"), "c.name")
//                       ->from(array("link" => "model_to_categories"), "*")
//                       ->where("link.id_model=?",$id_model)
//                       ->where("link.id_category = c.id")
//                       ->order("sort asc");
//        
//        return $this->fetchAll($select)->toArray(); 
//    }
//    
//    function getCategoriesByVideo($id_video){
//        
//        $select = $this->select()
//                       ->setIntegrityCheck(false)
//                       ->from(array("c" => "categories"), "c.name")
//                       ->from(array("link" => "video_to_categories"), "*")
//                       ->where("link.id_video=?",$id_video)
//                       ->where("link.id_category = c.id")
//                       ->order("sort asc");
//        
//        return $this->fetchAll($select)->toArray(); 
//    }
//    
//    /**
//    * 
//    * @param mixed $id_model
//    * @param mixed $cats 
//    */
//    function addCategoryForModel($id_model,$cats){
//        if(!$id_model || !count($cats)) return false;
//        
//        $this->getAdapter()->query("delete from model_to_categories where ".$this->getAdapter()->quoteInto("id_model=?", $id_model));
//        
//        $i = 0;
//        foreach($cats as $cat){
//            if(!$cat) continue;
//            $i++;
//            $this->getAdapter()->query("insert into model_to_categories set ".$this->getAdapter()->quoteInto("id_model=?", $id_model)." ,
//                                                                         ".$this->getAdapter()->quoteInto("id_category=?", $cat)." ,
//                                                                         ".$this->getAdapter()->quoteInto("sort=?", $i) );    
//        }
//    }

}    