<?php

class Transfer_wall extends App_Model
{

    protected $_name = "transfer_wall";

    protected $_primary = "id";

    /**
     * @param null $receiver
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getUserTransfers($receiver = null)
    {

        if ($receiver == 'user') {
            $receiverField = "username";
        }
        if ($receiver == 'model') {
            $receiverField = "screen_name";
        }

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('transfer_wall', array('date', 'amount'))
            ->joinLeft(array("c" => "user"), 'transfer_wall.sender_id=c.id', array('c.username AS sender', 'c.id AS sender_id'))
            ->joinLeft(array('d' => $receiver), 'transfer_wall.receiver_id=d.id', array('d.' . $receiverField . ' AS receiver', 'd.id AS receiver_id', 'd.active AS status'))
            ->where('transfer_wall.type = ?', $receiver)
            ->order('transfer_wall.amount DESC');

        return $this->fetchAll($select);

    }

    /**
     * @param null $amount
     * @param null $user_id
     */
    public function updateSenderChips($amount = null, $user_id = null)
    {

        $this->getAdapter()->query("update user set chips=(chips - " . $amount . ")  WHERE " . $this->getAdapter()->quoteInto("id=?", $user_id));

    }

}

