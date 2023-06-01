<?

class Static_pages extends App_Model{

    protected $_name = "static_pages";

    protected $_primary = "id";

    public function getContent($page , $type = 'frontend', $status = 1){
        $select = $this->select()
                       ->from($this->_name, array("*"))
                       ->where("page=?", $page)
                       ->where("type=?", $type);

        if (!is_null($status)) {
            if ($status == 1) {
                $select->where("status=?", $status);
            }
        }
        return $this->fetchRow($select);

    }

    public function getPages($type = 'frontend', $parent = null, $status = 1){

        $select = $this->select()
                       ->from($this->_name, array("*"))
                       ->where("type=?", $type);

        if (!is_null($parent)) $select->where("parent=?", $parent);
        else $select->where("parent IS NULL");

        if (!is_null($status)) {
            if ($status == 1) {
                $select->where("status=?", $status);
            }
        }

        return $this->fetchAll($select);

    }
}