<?

class Call_log extends App_Model{
    
    protected $_name="call_log";
    
    protected $_primary="id";
    
    /**
    * add new cals
    * 
    * @param mixed $data
    */
    
    public function addCall(array $data)
    {
        if(!is_array($data) || empty($data)) return false;        
        
        $this->insert($data);                             
        return;        
    }
    
    /**
    * update call
    * 
    * @param mixed $data
    * @param mixed $id
    */
    public function updateCall(array $data, $sid = null)
    {
        if(!is_array($data) || empty($data) || !$sid) return false;                
        $this->update($data, "call_sid='".$sid."'");        
    }
    
    /**
    * get active calls
    * 
    */
    public function getActiveCalls()
    {
        $select = $this->select()
                        ->where('status="in-progress"');
                        
        return $this->fetchAll($select);                                                
    }
    
    /**
    * get call by call id sid
    * 
    * @param mixed $sid
    */
    public function getCallBySid($sid = null)
    {
        if(!$sid) return false;
        
        return $this->fetchRow($this->select->where("call_sid=?",$sid));
    }
    
    
                              
}