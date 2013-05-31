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

	public function reindexImages($id)
	{
		$db = $this->getResource();
		$entry = new PaperRoll_Model_Entry();
		$inserts = array();
		$entry->load($id);
		$file = $entry->getData('filename');
		if($file == ""){
			$file = $db->fetchAll($db->select()->where('entry_id = ?', $id)->limit(1))->toArray();
			if(count($file) == 0){
    			throw new Zend_Exception("No filename found.");
			}
			$file = array_pop($file);
			if(isset($file['path'])){
				$entry->setData('filename', $file['path']);
				$entry->save();
			}
		}		
		$files = glob(APPLICATION_PATH."/../public/gallery/*/".$file);
		$images = $this->getEntryImages($id);
		if(count($files) == count($images)){
			return $inserts;
		} else {
			foreach($files as $k => $v){
			    $kind = dirname($v);
			    $kind = array_pop(explode("/", $kind));
			    unset($files[$k]);
				$files[$kind] = str_replace(APPLICATION_PATH."/..", "", $v);
			}
			foreach($images as $k => $v){
			    $kind = $v->getKind();
				$images[$kind->getPath()] = $this->_url . $kind->getPath() . "/" . $v->getPath();
			}
			$kinds = new PaperRoll_Model_ImageKind();
			$kinds = $kinds->getKinds();
			$kinds = array_combine($kinds, array_keys($kinds));
			foreach(array_diff($files, $images) as $new){
				$parts = explode("/", $new);
				$kind = $parts[3];
				if(isset($kinds[$kind])){
				    $data = array(
						'entry_id' 	=> $id,
						'path'		=> $file,
						'kind'		=> $kinds[$kind]
					);
					$db->insert($data);
					$inserts[] = $kind;
				}
			}
		}
		return $inserts;
	}

	public function load($id)
	{
		parent::load($id);
		$kind = new PaperRoll_Model_ImageKind();		
		$kind = $kind->load($this->getKind());
		$this->setKind($kind);
		$this->setUrl(Paper::getBaseUrl() . $this->_url . $kind->getPath() . "/" . $this->getPath());
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