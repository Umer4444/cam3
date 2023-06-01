<?

class Autoresponders extends App_Model
{

    protected $_name = "autoresponders";

    protected $_primary = "id";

    const QUESTION = "question";

    const ANSWER = "answer";

    public function searchAutoResponders($search, $type = self::QUESTION)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("a" => "autoresponders"), "*")
            ->where("message like ?", "%" . $search . "%")
            ->limit(25);

        return $this->fetchAll($select)->toArray();
    }

}    
