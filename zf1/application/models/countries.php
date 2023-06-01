<?

class Countries extends App_Model{
    
    protected $_name="countries";
    
    protected $_primary="id";
    
    public function getIdFromCode($code){
        return $this->fetchRow($this->select()
                                    ->from($this->_name, "id")
                                    ->where("code=?", $code)
                                    )->id;
    }
    
    public function getLocationById($id = null){
        if(!$id) return false;
        $location = $this->fetchRow($this->select()
                                    ->where("id=?", (int)$id)->order("name ASC")
                                    );
        if(count($location) > 0) {
            $location = $location->toArray();
            $location["name"] = convertAccent($location["name"]);
        } else {

            $location = null;
        }

        return (object)$location;
    }
    
    public function fetchAllLocations($type = 'CO'){
        $select = $this->select()->where("type=?", strtoupper($type))->order("name ASC");
        return $this->fetchAll($select);
    }
    
    public function locationAutocomplete($type = null,  $query = null, $in_location = null) {
        if(!$type || !$query)
            return false;
                         
        $resultSet = $this->select()
                            ->where("type=?", strtoupper($type))
                            ->where("name LIKE ?", '%'.$query.'%');
            if($in_location)
                    $resultSet->where("in_location=?", $in_location) ;
                    
                    $resultSet->limit(5)->order("name ASC");

        $result = $this->fetchAll($resultSet);

        $arr = array();
        foreach($result as $row){  
            $arr[] =  array(
                'id' => $row->id,
                'name' => convertAccent($row->name),
                'code' => convertAccent($row->code),
            );
        } 
        return json_encode($arr);
    }
    
    public function countriesArray(){
        
        $countries = self::fetchAllLocations("CO");
        $countriesArr = array('' => ' -- ');
        foreach($countries as $c){
            $countriesArr[$c->id] =  convertAccent(substr($c['name'],0,35));
        }
        
        return   $countriesArr;
    }    
    
}