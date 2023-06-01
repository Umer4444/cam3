<?

class Webcam_access extends App_Model{
    
    protected $_name="webcam_access";
    
    protected $_primary="id"; 

    public function getField($name){
        
        $select = $this->select()
                       ->from($this->_name)
                       ->where("type=?", $name);
        
        return $this->fecthRow($select)->id;
        
    }

}