<?

class Autoresponders_train extends App_Model
{

    protected $_name = "autoresponders_train";

    protected $_primary = "id";

    /**
     * @param $id_model
     * @return array
     */
    public function getAutorespondersByModel($id_model)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("a" => "autoresponders"), "*")
            ->from(array("t" => "autoresponders_train"), array("id_model", "id_question", "id_answer"))
            ->where("t.id_model=?", $id_model)
            ->where("a.id=t.id_question")
            ->group("t.id_question")
            ->order("t.id_question desc");

        $rows = $this->fetchAll($select);

        $autoresponders = array();
        $i = -1;
        $check_id = '';
        foreach ($rows as $row) {

            if ($check_id != $row->id_question) {
                $i++;
            }

            $check_id = $row->id_question;
            $autoresponders[$i]['id_question'] = $row->id;
            $autoresponders[$i]['question'] = $row->message;

            $answers = $this->getAutorespondersByIdQuestion($row->id_question);

            if (count($answers)) {
                $autoresponders[$i]['answers'] = $answers;
            }

        }

        return $autoresponders;

    }

    /**
     * @param $id_question
     * @return array
     *
     */
    public function getAutorespondersByIdQuestion($id_question)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("a" => "autoresponders"), "*")
            ->from(array("t" => "autoresponders_train"), array("id_model", "id_question", "id_answer"))
            ->where("t.id_question=?", $id_question)
            ->where("a.id=t.id_answer");
        return $this->fetchAll($select)->toArray();

    }

}