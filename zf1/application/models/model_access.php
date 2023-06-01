<?

class Model_access extends App_Model
{

    protected $_name = "model_access";

    protected $_primary = "id";

    public function getField($name)
    {

        $select = $this->select()
            ->from($this->_name)
            ->where("type=?", $name);

        return $this->fecthRow($select)->id;

    }

    public function getFieldbyId($id)
    {

        $select = $this->select()
            ->from($this->_name)
            ->where("id=?", $id);

        return $this->fecthRow($select)->type;

    }

}