<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

abstract class PaperRoll_Model_Core_Mapper
{

    protected $_dbTable;
    protected $_model;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

	/**
	 * @return Zend_Db_Table
	 */
    public function getDbTable()
    {
        return $this->_dbTable;
    }
 
    public function save(PaperRoll_Model_Core_Object $object)
    {
		$data = $object->getData();

		$data['modified_at'] = date('Y-m-d H:i:s');
        if (null === ($id = $object->getId())) {
            $data['created_at'] = $data['modified_at'];
        }

		$columns = $this->getDbTable()->fetchNew()->toArray();
		foreach($columns as $key => $value){
			if(isset($data[$key])) {
				$columns[$key] = $data[$key];
			} else {
				unset($columns[$key]);
			}
		}
        if (null === ($id = $object->getId())) {
            $id = $this->getDbTable()->insert($columns);
        } else {
            $this->getDbTable()->update($columns, array('id = ?' => $id));
        }
		return $id;
    }
 
    public function load($id, PaperRoll_Model_Core_Object $object)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $object->setData($row->toArray());
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        return $this->loadEach($resultSet);
    }

	public function loadEach($resultSet)
	{
		$entries   = array();
        foreach ($resultSet as $row) {
            $entry = new $this->_model;
            $entry->load($row->id);
            $entries[] = $entry;
        }
        return $entries;
	}
    
}