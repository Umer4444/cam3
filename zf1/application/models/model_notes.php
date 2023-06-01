<?

class Model_notes extends App_Model{

    protected $_name="model_notes";

    protected $_primary="id_model"; //dummy

    function getNotesByModel($id_model){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("n" => $this->_name), "*")
                       ->where("n.id_model=?", $id_model);
        return $this->fetchAll($select);
    }

    public function setNotes($model_id, $notes){


        $_info = $this->getNotesByModel($model_id);
        $deleted = false;
        if (!$notes || $notes == ''){ //if the field is empty delete from db if it exists
            $this->delete("id_model=".$model_id);
            $deleted = true;
        }
        else{
            if($_info->id_model){ //we have previous values
                $this->update(array("notes" => $notes,  "added" => time()), "id_model=".$model_id);

            }else {//we insert new value
                $this->insert(array("notes" => $notes, "id_model" => $model_id, "added" => time()) );
            }
        }

        $result = $this->getNotesByModel($model_id);
        if($result) $result = $result->toArray();

        if(count($result)>0) $result["saved"] = true;

        else{
            if($deleted) $result["saved"] = true;
            else $result["saved"] = false;
        }

        return $result;
    }

    /* cud */
    private function addItem($id_model = null, $note = null){
        if(!$id_model || !$note) return false;
        $date = new DateTime();
        return $this->insert(array("id_model" => (int)$id_model, "notes" => $note, "added" => $date->format('Y-m-d H:i:s')));
    }

    private function updateItem( $id_model = null, $note = null, $id_note = null){
        if(!$id_model || !$note || !$id_note) return false;
        return $this->update(array("notes" => $note, "added"=> time()), new Zend_Db_Expr("id = ".(int)$id_note." AND id_model=".(int)$id_model));
    }

    public function deleteItem($id_item = null, $id_model){
        if(!$id_item  || !Auth::isModel() || !$id_model) return false;
        return $this->delete("id=".(int)$id_item);
    }

    public function saveItem($id_model = null, $note = null, $id_note = null){

        if(!$id_model || !$note || !Auth::isModel()) return false;

        if($id_note)
            $action = $this->updateItem($id_model, $note, $id_note);
        else
            $action = $this->addItem($id_model, $note);

        return $action;

    }

}