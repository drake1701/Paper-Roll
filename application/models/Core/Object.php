<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

abstract class PaperRoll_Model_Core_Object
{

	protected $_data = array();
	static $_underscoreCache = array();
	protected $_dbTable;
	
 	public function __construct(array $data = null)
    {
        if (is_array($data)) {
            $this->setData($data);
        }
    }
    
    public function load($id){
    	$this->getMapper()->load($id, $this);
    	return $this;
    }

    public function save(){
    	$id = $this->getMapper()->save($this);
    	return $id;
    }

	public function delete(){
		if($this->getData('id')){
			$db = $this->getResource();
			$db->delete($db->getAdapter()->quoteInto("id = ?", $this->getData('id')));
		}
	}
 
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get' :
                $key = $this->_underscore(substr($method,3));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                return $data;

            case 'set' :
                $key = $this->_underscore(substr($method,3));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                return $result;

            case 'has' :
                $key = $this->_underscore(substr($method,3));
                return isset($this->_data[$key]);
        }
        throw new Zend_Exception("Invalid method ".get_class($this)."::".$method."(".print_r($args,1).")");
    }

    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }
    
    public function setData($key, $value=null)
    {
        $this->_hasDataChanges = true;
        if(is_array($key)) {
            $this->_data = $key;
        } else {
            $this->_data[$key] = $value;
        }
        return $this;
    }
    
    public function getData($key = null)
    {
        if($key == null) {
			return $this->_data;
		} else {
			return isset($this->_data[$key]) ? $this->_data[$key] : null;
		}
    }
    
	public function fetchAll(){
		return $this->getMapper()->fetchAll();	
	}
	
	public function getMapper(){
		$class = get_class($this)."_Mapper";
		return new $class;
	}

	/*
	 * @returns Zend_Db_Table_Abstract
	 */
	public function getResource(){
		return $this->getMapper()->getDbTable();
	}
    
}