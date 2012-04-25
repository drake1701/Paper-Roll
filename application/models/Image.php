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
		$db = $this->getResource();
		$images = $db->fetchAll($db->select()
			->from("image")
			->join(array("k" => "image_kind"), "image.kind = k.id", null)
			->where("entry_id = $id")
			->order("k.position ASC"));
		$images = $this->getMapper()->loadEach($images);
		$temp = array();
		foreach($images as $image){
			$temp[$image->getKind()->getPath()] = $image;
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