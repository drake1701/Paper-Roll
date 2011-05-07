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
 
    public function getDbTable()
    {
        return $this->_dbTable;
    }
 
    public function save(PaperRoll_Model_Core_Object $object)
    { 
        if (null === ($id = $entry->getId())) {
            unset($data['id']);
            $data['updated_at'] = $data['created_at'] = date('Y-m-d H:i:s');
            $this->getDbTable()->insert($data);
        } else {
        	$data['updated_at'] = date('Y-m-d H:i:s');
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
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
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new $this->_model;
            $entry->load($row->id);
            $entries[] = $entry;
        }
        return $entries;
    }
    
}