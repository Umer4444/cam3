<?
class Chips extends App_Model
{
    protected $_name = "chips";

    protected $_primary = "id";

    public function useChips($id_model, $amount=null, $id_user = null,$type)
    {

        if(!$amount) return false;

        $id_user = $id_user ? $id_user : $_SESSION['user']['id'];

        if($this->getChips($id_user) < $amount) return false;

        $this->insert(array("id_sender" => $id_user,
                                        "id_receiver" => $id_model,
                                        "data" => time(),
                                        "amount" => (int)$amount,
                                        "type" => $type
                                        ));

        $this->getAdapter()->query("update user set chips=(chips + ".$amount.")  WHERE ".$this->getAdapter()->quoteInto("id=?",$id_model));
        $this->getAdapter()->query("update user set chips=(chips - ".$amount.")  WHERE ".$this->getAdapter()->quoteInto("id=?",$id_user));

        return true;
    }

    public function getChips($id_user = null, $id_model = null)
    {

        if($id_user){
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array("u" => "user"), "chips")
                           ->where("user_id=?",$id_user);

            return round($this->fetchRow($select)->chips,2);


        }

        if($id_model){
            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from(array("m" => "user"), "chips")
                           ->where("id=?",$id_model);

            return $this->fetchRow($select)->chips;


        }

        //return '0';
    }

    public function addChipsUser($id_user,$amount)
    {
        //update user total chips after a successfull payment
        $this->getAdapter()->query("update user set chips=(chips + ".$amount.")  WHERE ".$this->getAdapter()->quoteInto("id=?",$id_user));
    }

    public function fetchLastActivity($last_h = 0)
    {

        $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from($this->_name, array('total_amount' => 'sum(amount)',"id_receiver" => "id_receiver","type" =>"type", "time" => "from_unixtime( data, '%Y%m%d%H')"))
                           ->where("data >= ?",(time()-(3600*$last_h)))
                           ->group(array("id_receiver","type","from_unixtime( data, '%Y%m%d%H')"))
                           ;

        return $this->fetchAll($select);
    }

    public function getParity()
    {
        return array("parity" => $this->config('chips_parity'), "currency" => "USD");
    }

    public function convertChipsToMoney($amount)
    {
        $parity = $this->getParity();
        return floor($amount / $parity['parity']);
    }

    public function convertMoneyToChips($amount)
    {
        $parity = $this->getParity();
        return floor($amount * ($parity['parity']) );
    }
    public function getUserTransfers($receiver = null)
    {

        $type = 'transfer';
        if ($receiver == 'user') {
            $receiverField = "username";
        }
        if ($receiver == 'model') {
            $receiverField = "screen_name";
        }


            $select = $this->select()
                           ->setIntegrityCheck(false)
                           ->from('chips',array('data','amount'))
                           ->joinLeft(array("c" => "user"),'chips.id_sender=c.id',array('c.username AS sender', 'c.id AS sender_id'))
                           ->joinLeft(array('d' => $receiver),'chips.id_receiver = d.' . $receiverField,array('d.'.$receiverField.' AS receiver', 'd.' . $receiverField . ' AS receiver_id'/*,'d.active AS status'*/))
                           //there should be user_id instead of
                           //receiver field first and third time it is used in the query!
                           ->where('chips.receiver_type = ?', $receiver)
                           ->where('chips.type = ?', $type)
                           ->order('chips.amount DESC');

            return $this->fetchAll($select);

    }

    public function updateSenderChips($amount = null, $user_id = null)
    {
        $this->getAdapter()->query("update user set chips=(chips - ".$amount.")  WHERE ".$this->getAdapter()->quoteInto("id=?",$user_id));

    }
    public function updateReceiverChips($amount = null, $receiver_id = null, $receiver_type = null)
    {

        $this->getAdapter()->query("update ".$receiver_type." set chips=(chips - ".$amount.")  WHERE ".$this->getAdapter()->quoteInto("id=?",$receiver_id));

    }

    public function getSenderChips($sender_id = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from('chips',array('SUM(amount) AS limit', "from_unixtime( data, '%Y%m%d') AS date_limit"))
                       //->where('chips.data = ?', date("Ymd", time()))
                       ->where('chips.id_sender = ?', $sender_id)
                       ->where("chips.type='transfer'");

            return $this->fetchrow($select);

    }

}