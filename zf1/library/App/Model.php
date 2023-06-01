<?

class App_Model extends Zend_Db_Table_Abstract{

	protected $_cache = true;
	protected $_dontCacheNextQuery = false;

	public function __construct($config = array()){
		$this->cache = Zend_Registry::get("dbCache");
		parent::__construct($config);
	}

	public function setCache($status = true){
		$this->_cache = $status;
	}

	public function dontCacheNextQuery(){
		$this->_dontCacheNextQuery = true;
	}

	/**
	* @param mixed $method
	* @param mixed $arguments
	* @returns $this->cache
	*/
	public function __call($method,$arguments){
		if(substr($method,0,1)=="_" && !method_exists($this,$method) && method_exists($this,substr($method,1))){
			$this->dontCacheNextQuery();
			return call_user_func_array(array($this,substr($method,1)),$arguments);
		}
	}

	public function fetchAll($where = null, $order = null, $count = null, $offset = null){

		if(!$this->_cache || $this->_dontCacheNextQuery){
			$this->_dontCacheNextQuery=false;
			return parent::fetchAll($where, $order, $count, $offset);
		}

		if(!($return = $this->cache->load(md5($this->_name.__FUNCTION__.($where instanceof Zend_Db_Table_Select?$where->__toString().$order.$count.$offset:serialize(func_get_args())))))){
			$return = parent::fetchAll($where, $order, $count, $offset);
			$this->cache->save($return);
		}

		return $return;

	}

	public function fetchRow($where = null, $order = null , $offset = null){

		if(!$this->_cache || $this->_dontCacheNextQuery){
			$this->_dontCacheNextQuery=false;
			return parent::fetchRow($where, $order, $offset);
		}

		if(!($return = $this->cache->load(md5($this->_name.__FUNCTION__.($where instanceof Zend_Db_Table_Select?$where->__toString().$order.$offset:serialize(func_get_args())))))){
			$return = parent::fetchRow($where, $order, $offset);
			$this->cache->save($return);
		}

		return $return;

	}

	public function find(){

		if(!$this->_cache || $this->_dontCacheNextQuery){
			$this->_dontCacheNextQuery=false;
			return call_user_func_array(array(parent,"find"),func_get_args());
		}

		if(!($return = $this->cache->load(md5($this->_name.__FUNCTION__.serialize(func_get_args()))))){
			$return = call_user_func_array(array(parent,"find"),func_get_args());
			$this->cache->save($return);
		}

		return $return;

	}

	public function update(array $data, $where){

		$this->cache->clean();
		return parent::update($data, $where);
	}

	public function delete($where){
		$this->cache->clean();
		return parent::delete($where);
	}

	public function insert(array $data){
		$this->cache->clean();
		return parent::insert($data);
	}

}