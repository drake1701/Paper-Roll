<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class PaperRoll_Model_Entry extends PaperRoll_Model_Core_Object
{
	
	public function load($id){
		parent::load($id);
		$this->setUrl("/entry/view?e=".$id);
		$images = new PaperRoll_Model_Image();
		$this->setImages($images->getEntryImages($id));
		return $this;		
	}

}