<?

class Model_rates extends App_Model{
    
    protected $_name="model_rates";
    
    protected $_primary="id_model"; //dummy
    
    //scos si limite + limite speciale
    public function getRatesByModel($id_model = null){
        $select = db()->query("select m.value,
                                      coalesce(m1.value, 0) as value_pending,  
                                      (select l.value from rates_limits as l where l.id_rate=r.id and l.limit_type=\"min\") as min , 
                                      (select l1.value from rates_limits as l1 where l1.id_rate=r.id and l1.limit_type=\"max\") as max,
                                      (select l2.value from rates_limits as l2 where l2.id_rate=r.id and l2.limit_type=\"default\") as default_value,
                                      r.type,
                                      coalesce(m.special, 0) as special, 
                                      r.id
                                      ".(!is_null($id_model) ? ", ".$id_model." as id_model " : "")." 
                               from rates as r 
                               left join model_rates_pending as m1 on (r.id=m1.id_rate ".(!is_null($id_model) ? "and m1.id_model=".$id_model.")" : ")")."
                               left join model_rates as m on (r.id=m.id_rate ".(!is_null($id_model) ? "and m.id_model=".$id_model.")" : ") group by m.id_model,r.id "));

        return $select->fetchAll();
    }
    
    public function initRatesByModel($id_model){
        $rates = $this->getRatesByModel($id_model);
        if(is_array($rates)){
            if(count($rates)>0){
                foreach($rates as $rate){
                    if($rate['value'] == 0 && !is_null($rate['value'])){
                        db()->update($this->_name, array("value" => $rate['min']), db()->quoteInto("id_model=?", $id_model)." and ".db()->quoteInto("id_rate=?", $rate['id']));
                    }else{
                        if(is_null($rate['value'])){
                            db()->insert($this->_name, array("value" => $rate['min'], "id_model" => $id_model, "id_rate"=> $rate['id']));
                        }
                    }
                    
                }
            }
        }
    }
    
    public function getModelRateById($id_model, $id_rate){
        return $this->fetchRow($this->select()->where("id_model=?", $id_model)->where("id_rate=?", $id_rate ));
    }
    
    public function getModelRateByType($id_model, $type){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("m" => $this->_name), array("value"))
                       ->from(array("r" => "rates"), array("type", "id"))
                       ->where("r.id=m.id_rate");
                       
        if(!is_null($id_model)) $select->where("m.id_model=?", $id_model);
        
        if(!is_null($type)) $select->where("r.type=?", $type);
        
        else $select->group("m.id_model");
                       
        return $this->fetchAll($select);
    }
}