<?

class Model_rates_pending extends App_Model{
    
    protected $_name="model_rates_pending";
    
    protected $_primary="id_model"; //dummy
    
    function getModelRateById($id_model, $id_rate){
        return $this->fetchRow($this->select()->where("id_model=?", $id_model)->where("id_rate=?", $id_rate ));
    }
    
    //scos si limite + limite speciale
    public function getRatesByModel($id_model = null){
        $select = db()->query("select m.value,
                                      r.type,
                                      r.id
                                      ".(!is_null($id_model) ? ", ".$id_model." as id_model " : "")." 
                               from rates as r 
                               inner join ".$this->_name." as m on (r.id=m.id_rate ".(!is_null($id_model) ? "and m.id_model=".$id_model.")" : ") group by m.id_model,r.id "));

        return $select->fetchAll();
    }
}