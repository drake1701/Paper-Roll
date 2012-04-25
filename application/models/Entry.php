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
		$this->prepareTags();
		return $this;
	}

	public function save()
	{
		if(!is_array($this->getData('tags'))){
			$slugs = explode(",", $this->getData('tags'));
			$tag = new PaperRoll_Model_Tag();
			$tag->checkLinks($this->getId(), $slugs);
		}
		parent::save();
	}

	public function getImageUrl($type){
		$images = $this->getImages();
		if(isset($images[$type])){
			return $images[$type]->getUrl();
		}
		return '';
	}

	public function getFirstImage(){
		foreach($this->getImages() as $image){
			if($image->getKind()->getPosition() != null){
				return $image;
			}
		}
		return '';
	}

	public function getVisible()
	{
		return $this->getMapper()->getVisible();
	}

	public function getLatest($count = 1)
	{
		return $this->getMapper()->getLatest($count);
	}
	
	public function canShow(){
		if(!$this->load($this->getId())){
			return false;
		}
		return true;
	}

	public function prepareTags(){
		// get tags from db
		$tag = new PaperRoll_Model_Tag();
		$tags = $tag->getEntryTags($this);
		$this->setTags($tags);
		return $this;
	}

}