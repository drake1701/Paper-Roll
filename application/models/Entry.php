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
		$images = $images->getEntryImages($id);
		$this->setImages($images);
		if(!isset($images['thumb']) || !isset($images['preview'])){
			Bootstrap::log("Required images not found for Entry $id");
			return false;
		}		
		$this->setPreview($images['preview']);
		$this->setThumb($images['thumb']);
		return $this;		
	}
	
	public function canShow(){
		if(!$this->load($this->getId())){
			return false;
		}
		return true;
	}

}