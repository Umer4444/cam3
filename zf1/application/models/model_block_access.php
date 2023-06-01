<?

class Model_block_access extends App_Model
{

    protected $_name = "model_block_access";

    protected $_primary = "id";

    public function getAccessRules($id, $id_country = null, $state = null, $city = null)
    {

        $select = $this->select()
            ->from($this->_name)
            ->where("id_model=?", $id);

        if (!is_null($id_country)) $select->where("id_country=?", $id_country);
        if (!is_null($state)) $select->where("state=?", $state);
        if (!is_null($city)) $select->where("city=?", $city);
        return $this->fetchAll($select);

    }

    public function getRule($id)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("r" => $this->_name), array("*"))
            ->from(array("c" => "countries"), array("code"))
            ->where("r.id_country=c.id")
            ->where("r.id=?", $id);
        return $this->fetchRow($select);

    }
}