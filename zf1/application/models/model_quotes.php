<?

class Model_quotes extends App_Model{

    protected $_name="model_quotes";

    protected $_primary="id";

    public function quoteSuggest($query = null) {


        $select = $this->select()->setIntegrityCheck(false)
                            ->from(array("m" => $this->_name), array("id", "text"));
        if($query)
            $select->where("m.text LIKE ?", $query.'%');

       if($_SESSION["group"] == "model") {
           $select->where("m.id_model=?", $_SESSION["user"]["id"]);
        }

        //$select->limit(10);

        $result = $this->fetchAll($select);

        $arr = [];
        foreach($result as $row){
            $arr[$row->id] = $row->text;
            //$arr[] =  array('id' => $row->id, 'text' => utf8_encode($row->text));
        }
        return $arr;
    }

    public function deleteQuotes($notifications = null){
        if(!$notifications) return false;
        $notifications = trim($notifications, ",");
        $this->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '".$notifications."') > 0"));
    }

}