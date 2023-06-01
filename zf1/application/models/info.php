<?

class Info extends App_Model
{

    protected $_name = "info";

    protected $_primary = "id";

    public function getArray()
    {

        $arr = $this->fetchAll($this->select())->toArray();
        $return_arr = array();
        foreach ($arr as $row) {
            $return_arr[$row["field"]] = $row["default_values"];
        }

        return $return_arr;
    }

    public function getArrayFields()
    {

        $arr = $this->fetchAll($this->select())->toArray();
        $return_arr = array();
        foreach ($arr as $row) {
            $return_arr[$row["id"]] = $row["field"];
        }

        return $return_arr;
    }
}