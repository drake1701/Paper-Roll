<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_Model_Config_Mapper extends PaperRoll_Model_Core_Mapper
{
	public function __construct(){
		$this->setDbTable('PaperRoll_Model_Config_DbTable_Config');
		$this->_model = 'PaperRoll_Model_Config';
	}
}
