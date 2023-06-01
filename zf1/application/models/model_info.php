<?

class Model_info extends App_Model
{

    protected $_name = "model_info";

    protected $_primary = "id_model"; //dummy


    function getModelInfo($id_model)
    {
        return $this->fetchAll($this->select()->where("id_model=?", $id_model));
    }

    function getModelInfoById($id_model, $id_field)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("*"))
            ->where("m.id_model=?", $id_model)
            ->where("m.id_field=?", $id_field)
            ->joinRight(array("i" => "info"), "i.id=m.id_field", array("field"));

        return $this->fetchRow($select);
        // $select = $this->select()
//                       ->setIntegrityCheck(false)
//                       ->from(array("m" => $this->_name), array("*"))
//                       ->where("m.id_model=?",$id_model)
//                       ->where("m.id_field=?",$id_field )
//                       ->joinLeft(array("i" => "info"), "i.id=m.id_field", array("field"))
//                       
//                       ;
//        return $this->fetchRow($select);
        //return $this->fetchRow($this->select()->where("id_model=?",$id_model)->where("id_field=?",$id_field ));
    }

    public function getInfoByModel($id_model = null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("i" => "info"), array("*"))
            ->joinLeft(array("m" => $this->_name), "i.id=m.id_field" . ($id_model ? " AND m.id_model=" . $id_model : ""), array("value", "id_model"));

        if (is_null($id_model)) $select->group("m.id_model");
        return $this->fetchAll($select);

    }
}