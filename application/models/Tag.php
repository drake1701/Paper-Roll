<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_Model_Tag extends PaperRoll_Model_Core_Object {

	public function getEntryTags($entry)
	{
		$db = $this->getResource();
		$tags = $db->fetchAll($db->select()
			->from("tag")
			->join(array("m" => "entry_tag"), "tag.id = m.tag_id", array())
			->join(array("e" => "entry"), "e.id = m.entry_id", array())
			->where("e.id = ?", $entry->getId()))->toArray();
		foreach($tags as $key => $tag){
			$tags[$key]['url'] = "/tag/".$tag['slug'];
		}
		return $tags;
	}

	public function checkLinks($entryId, $slugs)
	{
		// Current & New
		foreach($slugs as $slug){
			$slug = trim($slug);
			$tag = $this->loadBySlug($slug);
			$db = new Zend_Db_Table('entry_tag');
			$links = $db->fetchAll($db->select()
				->where('tag_id = ?', $tag->getId())
				->where('entry_id = ?', $entryId));
			if($links->count() == 0){
				$db->insert(array("tag_id" => $tag->getId(), "entry_id" => $entryId));
			}
		}
		// deletes
		$entry = new PaperRoll_Model_Entry();
		$entry->load($entryId);
		$current = $this->getEntryTags($entry);
		foreach($current as $tag){
			if(!in_array($tag['slug'], $slugs)){
				$result = $db->fetchRow($db->select()
					->where("tag_id = ?", $tag['id'])->where("entry_id = ?", $entryId))->toArray();
				$db->delete($db->getAdapter()->quoteInto("id = ?", $result['id']));
			}
		}

	}

	public function loadBySlug($slug)
	{
		$db = $this->getResource();
		$result = $db->fetchRow($db->select()->where('slug = ?', $slug));
		if($result['id']){
			return $this->load($result['id']);
		} else {
			$this->setData('slug', $slug);
			$this->setData('title', ucwords(preg_replace("#(-|_)#", " ", $slug)));
			$this->save();
			return $this;
		}
	}

	public function getYears()
	{
		$entry = new PaperRoll_Model_Entry();
		$db = $entry->getResource();
		$result = $db->fetchAll($db->select()
			->from("entry")
			->columns(array("year" => "LEFT(`published_at`, 4)"))
			->group("year")
			->order("published_at DESC")
		);
		$years = array();
		foreach($result as $year)
		{
			$years[] = $year['year'];
		}
		return $years;
	}

	public function getTop($count = 20)
	{
		$db = $this->getResource();
		$tags = $db->fetchAll($db->select()
			->from("tag")
			->join(array("m" => "entry_tag"), "m.tag_id = tag.id", null)
			->columns(array("count" => "COUNT(*)"))
			->group("tag_id")
			->order("count DESC")
			->limit($count)
		);
		return $this->getMapper()->loadEach($tags);
	}

	public function getIndex()
	{
		$db = $this->getResource();
		$tags = $db->fetchAll($db->select()
			->setIntegrityCheck(false)
			->from("tag")
			->join(array("m" => "entry_tag"), "m.tag_id = tag.id", null)
			->join(array("e" => new Zend_Db_Expr("(SELECT DISTINCT id FROM `entry` ORDER BY `published_at` DESC)")), "e.id = m.entry_id", array("entry_id" => "id"))
			->columns(array("count" => "COUNT(*)"))
			->order("tag.title")
			->order("m.entry_id DESC")
			->group("tag.id")
			->having("e.id = MAX(e.id)")
			->limit(24)
		);
		return $tags;
	}

	public function getEntries($tag)
	{
		$date = new PaperRoll_View_Helper_Date();
		$entry = new PaperRoll_Model_Entry();
		$db = $entry->getResource();
		// Check for named tags
		$entries = $db->fetchAll($db->select()
			->from("entry")
			->join(array("m" => "entry_tag"), "m.entry_id = entry.id", null)
			->join(array("t" => "tag"), "t.id = m.tag_id", null)
			->where("t.slug = ?", $tag)
			->where("entry.queue IS NULL")
			->order("entry.published_at DESC")
		);
		if($entries->count() == 0){
			// check for posts by year
			if(is_numeric($tag)){
				$entries = $db->fetchAll($db->select()
					->from("entry")
					->where("entry.queue IS NULL")
					->where("entry.published_at >= ?", "$tag-01-01 00:00:00")
					->where("entry.published_at <= ?", "$tag-12-31 23:59:59")
					->order("entry.published_at DESC")
				);
			} else {
				$kind = new PaperRoll_Model_ImageKind();
				$kinds = $kind->getKinds();
				if(in_array($tag, $kinds)){
					$kindId = array_search($tag, $kinds);
					$entries = $db->fetchAll($db->select()
						->from("entry")
						->join(array("i" => "image"), "i.entry_id = entry.id", null)
						->join(array("k" => "image_kind"), "i.kind = k.id", null)
						->where("k.id = ?", $kindId)
						->where("entry.queue IS NULL")
						->order("entry.published_at DESC")
					);
				}
			}
		}
		return $entry->getMapper()->loadEach($entries);
	}

}
