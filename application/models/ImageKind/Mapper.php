<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */


class PaperRoll_Model_ImageKind_Mapper extends PaperRoll_Model_Core_Mapper
{

	public function __construct(){
		$this->setDbTable('PaperRoll_Model_ImageKind_DbTable_ImageKind');
		$this->_model = 'PaperRoll_Model_ImageKind';
	}

}