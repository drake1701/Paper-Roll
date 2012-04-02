<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class PaperRoll_Model_Config extends PaperRoll_Model_Core_Object
{

	/*
	 * @params string $code
	 * @return mixed
	 */
	public function	getByCode($code)
	{
		$table = $this->getMapper()->getDbTable(); // @return Zend_Db_Table_Select
		$rows = $table->fetchAll($table->select()->where("code = ?", $code));
		$result = $rows->current()->toArray();
		if(isset($result['value'])){
			return $result['value'];
		}
		return '';
	}


}