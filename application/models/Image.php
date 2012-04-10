<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class PaperRoll_Model_Image extends PaperRoll_Model_Core_Object
{

	protected $_url = "/public/gallery/";

	public function getEntryImages($id)
	{
		$images = $this->getMapper()->getDbTable()->select()->where("entry_id = $id")->query()->fetchAll();
		$temp = array();
		foreach($images as $key => $image){
			$image = $this->load($image['id']);
			$temp[$image->getKind()->getPath()] = $this->_url . $image->getKind()->getPath() . '/' . $image->getPath();
		}
		return $temp;
	}
	
	public function load($id)
	{
		parent::load($id);
		$kind = new PaperRoll_Model_ImageKind();		
		$kind = $kind->load($this->getKind());
		$this->setKind($kind);
		$this->setUrl($this->_url . $kind->getPath() . "/" . $this->getPath());
		return $this;
	}

	public function getThumbImages()
	{
		$images = $this->getMapper()->getDbTable()->fetchAll($this->getMapper()->getDbTable()->select()->group('path'));
		$files = array();
		foreach($images as $image){
			$files[] = $image['path'];
		}
		return $files;
	}

}