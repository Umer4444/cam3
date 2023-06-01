<?

class Model_schedule extends App_Model{
    
    protected $_name="model_schedule";
    
    protected $_primary="id";
    
    
    public function getModelScheduleById($id_model,$only_future = NULL ,$type = NULL, $status = NULL){
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       //->from("model_schedule", array("CONCAT(date,'000') as date","type","title","description","url","to_date","id as id_item", "repeat", "repeat_for", "day"))
                       ->from("model_schedule", array("date","type","title","description","url","to_date","id as id_item", "repeat", "repeat_for", "day"))
                       ->where("id_model=?", $id_model)
                       ->where("date IS NOT NULL")
                       ->order("date asc");
                       
        if(!is_null($only_future)) $select->where("date>=?",time());
        if(!is_null($type)) $select->where("type=?", $type);
        if(!is_null($status)) $select->where("status=?", $status);
        
        //return $this->fetchAll($select)->toArray();
        //p( $this->fetchAll($select)->toArray(),1);
        $resultSet = $this->fetchAll($select);
        
        $dates = array();
        
        foreach($resultSet as $result){
            if($result->repeat == "daily"){
                $tmp_date = $result->date;
                $tmp_date = gmmktime(date('H', $tmp_date), date('i', $tmp_date), date('s', $tmp_date), date('n', $tmp_date), date('j', $tmp_date),  date('Y', $tmp_date));
                for($i=1; $i<=$result->repeat_for; $i++){
                    $dates[] = array(
                       "date" =>  strtotime ( "+{$i} day" , $tmp_date )."000", 
                       "type" => $result->type,
                       "title" => $result->title, 
                       "description" => $result->description, 
                       "url" => $result->url,
                       "to_date" => $result->to_date,
                       "id_item" => $result->id_item,
                       "repeat" => $result->repeat,
                       "repeat_for" => $result->repeat_for,
                       "day" => $result->day,
                   );
                }

            } elseif($result->repeat == "weekly") {
                $days = explode(",", $result->day);
               
               
               // $result->date = strtotime ( "last Sunday" , $result->date ); 
                
                foreach($days as $day){
                    $tmp_date = strtotime ( "next ".ucwords($day) , $result->date );
                    $tmp_date = gmmktime(date('H', $tmp_date), date('i', $tmp_date), date('s', $tmp_date), date('n', $tmp_date), date('j', $tmp_date),  date('Y', $tmp_date));
                    for($i=1; $i<=$result->repeat_for; $i++){
                        
                        $dates[] = array(
                           "date" =>  strtotime ( "+{$i} week" , $tmp_date )."000", 
                           "type" => $result->type,
                           "title" => $result->title, 
                           "description" => $result->description, 
                           "url" => $result->url,
                           "to_date" => $result->to_date,
                           "id_item" => $result->id_item,
                           "repeat" => $result->repeat,
                           "repeat_for" => $result->repeat_for,
                           "day" => $result->day,
                       );
       
                    }
                }
            } elseif($result->repeat == "monthly") {
                
                $tmp_date = $result->date;
                $tmp_date = gmmktime(date('H', $tmp_date), date('i', $tmp_date), date('s', $tmp_date), date('n', $tmp_date), date('j', $tmp_date),  date('Y', $tmp_date));
                
                for($i=1; $i<=$result->repeat_for; $i++){
                    $dates[] = array(
                       "date" =>  strtotime ( "+{$i} month" , $tmp_date )."000", 
                       "type" => $result->type,
                       "title" => $result->title, 
                       "description" => $result->description, 
                       "url" => $result->url,
                       "to_date" => $result->to_date,
                       "id_item" => $result->id_item,
                       "repeat" => $result->repeat,
                       "repeat_for" => $result->repeat_for,
                       "day" => $result->day,
                   ); 
                }
            }
            
            $tmp_date = $result->date;
            $tmp_date = gmmktime(date('H', $tmp_date), date('i', $tmp_date), date('s', $tmp_date), date('n', $tmp_date), date('j', $tmp_date),  date('Y', $tmp_date));
           $dates[] = array(
                   "date" => $tmp_date."000", 
                   "type" => $result->type,
                   "title" => $result->title, 
                   "description" => $result->description, 
                   "url" => $result->url,
                   "to_date" => $result->to_date,
                   "id_item" => $result->id_item,
                   "repeat" => $result->repeat,
                   "repeat_for" => $result->repeat_for,
                   "day" => $result->day,
               );
            
        }
   
        return $dates;
    }
    
    public function getScheduleEventById($id){
        return $this->fetchRow($this->select()->where("id=?", $id));
    }
}