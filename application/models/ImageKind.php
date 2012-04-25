<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class PaperRoll_Model_ImageKind extends PaperRoll_Model_Core_Object
{
	protected $_kinds;

	public function getKinds()
	{
		if(!isset($this->_kinds)){
			$kinds = array();
			foreach($this->fetchAll() as $kind){
				$kinds[$kind->getId()] = $kind->getPath();
			}
			$this->_kinds = $kinds;
		}
		return $this->_kinds;
	}

	public function getTagLinks()
	{
		$kinds = $this->getResource()->fetchAll($this->getResource()->select()->where('position is not null')->order("position", "asc"));
		return $this->getMapper()->loadEach($kinds);
	}
}