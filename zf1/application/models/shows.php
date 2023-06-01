<?

class Shows extends App_Model{
    
    protected $_name="shows";
    
    protected $_primary="id";
    

    public function getShows($date, $type = null, $status = null, $search = null){
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("s" => $this->_name), array("chips_cost", "chips_reserved", "nr_users", "type"))
                       ->from(array("m" => "model_schedule"), array("*"))
                       ->from(array("p" => "user"), array("screen_name"))
                       ->where("m.id_model=p.id")
                       ->where("m.id_show=s.id")
                       ->where("m.date>=?", $date)
                       ->order(array("m.date asc"));
        if($search) {
            $wh = $this->getAdapter()->quoteInto('p.screen_name like ?', strtolower("%".$search."%")). " OR ";
            $wh .= $this->getAdapter()->quoteInto('m.title like ?', strtolower("%".$search."%"));
            $select->where($wh);
        }
        
        if(!is_null($type)) $select->where("s.type=?", $type);
        if(!is_null($status)) $select->where("m.status=?", $status);

        return $this->fetchAll($select);
                        
    }
    
    public function getShowsDate($date, $to_date, $type = null, $status = null, $search = null){

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("s" => $this->_name), array("chips_cost", "chips_reserved", "nr_users", "type"))
                       ->from(array("m" => "model_schedule"), array("*"))
                       ->from(array("p" => "user"), array("screen_name"))
                       ->where("m.id_model=p.id")
                       ->where("m.id_show=s.id")
                       ->where("m.date>=?", $date)
                       ->where("m.to_date<=?", $to_date)
                       ->order(array("m.date asc"));
                       
        if(!is_null($type)) $select->where("s.type=?", $type);
        if(!is_null($status)) $select->where("m.status=?", $status);
        
        if($search) {
            $wh = $this->getAdapter()->quoteInto('p.screen_name like ?', strtolower("%".$search."%")). " OR ";
            $wh .= $this->getAdapter()->quoteInto('m.title like ?', strtolower("%".$search."%"));
            $select->where($wh);
        }

        return $this->fetchAll($select);
                        
    }
    
    public function getPastShows($model_id, $date, $type = null, $status = null, $search = null){
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("s" => $this->_name), array("chips_cost", "chips_reserved", "nr_users", "type"))
                       ->from(array("m" => "model_schedule"), array("*"))
                       ->from(array("p" => "user"), array("screen_name"))
                       ->where("m.id_model=p.id")
                       ->where("m.id_model=?", $model_id)
                       ->where("m.id_show=s.id")
                       ->where("m.date<=?", $date)
                       ->where("s.type=?", $type)
                       ->order(array("m.date desc"));
                       
        if(!is_null($type)) $select->where("s.type=?", $type);
        if(!is_null($status)) $select->where("m.status=?", $status);
        
        if($search) {
            $wh = $this->getAdapter()->quoteInto('p.screen_name like ?', strtolower("%".$search."%")). " OR ";
            $wh .= $this->getAdapter()->quoteInto('m.title like ?', strtolower("%".$search."%"));
            $select->where($wh);
        }
            
        return $this->fetchAll($select);
                        
    }

}