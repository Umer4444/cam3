<?
class Blog_categories extends App_Model{

    protected $_name = "categories";

    protected $_primary = "id";

    public function getAllByModelArray($id_model = null){

        if(!$id_model) return array();

        $resultSet = $this->fetchAll($this->select()->where("user=?", (int)$id_model));

        $arr = array();
        //$arr[] = ' -- ';
        foreach($resultSet as $row)
            $arr[$row->id] = $row->name;

        return $arr;
    }

    public function getByModelId($id_model = null){
        if(!$id_model) return false;

        $select = $this->select()->where("user=?" , (int)$id_model);
        return $this->fetchAll($select);
    }

    public function deleteMultiple($ids = null){
        if(!$ids) return false;
        $ids = trim($ids, ",");
        $this->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '".$ids."') > 0"));
    }
}