<?

class Model_user_notes extends App_Model{

    protected $_name="model_user_notes";

    protected $_primary="id_model"; //dummy

    function getNotesByModel($id_model){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array("n" => $this->_name), "*")
                       ->from(array("u" => "user"), array("display_name"))
                       ->where("n.id_user=u.id")
                       ->where("n.id_model=?", $id_model);
        return $this->fetchAll($select);
    }

    function getUserNotesById($id_model, $id_user){
        return $this->fetchRow($this->select()->where("id_model=?", $id_model)->where("id_user=?", $id_user));
    }

    public function setNotes($model_id, $user_id, $notes){


        $_info = $this->getUserNotesById($model_id, $user_id);
        $deleted = false;
        if (!$notes || $notes == ''){ //if the field is empty delete from db if it exists
            $this->delete("id_model=".$model_id." and id_user=".$user_id);
            $deleted = true;
        }
        else{
            if($_info->id_model){ //we have previous values
                $this->update(array("notes" => $notes, "added" => time()), "id_model=".$model_id." and id_user=".$user_id);

            }else {//we insert new value
                $this->insert(array("notes" => $notes, "id_model" => $model_id, "id_user" =>$user_id, "added" => time() ) );
            }
        }

        $result = $this->getUserNotesById($model_id, $user_id)->toArray();

        if(count($result)>0) $result["saved"] = true;
        else{
            if($deleted) $result["saved"] = true;
            else $result["saved"] = false;
        }

        return $result;
    }
}