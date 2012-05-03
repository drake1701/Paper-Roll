<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_Model_Queue {

	protected $types = array(
		'1' => 'm-th-sa',
		'2' => 'monthly-1'
	);

	public function getLastPublishDate($type){
		$last = new DateTime($this->getLastDate($type));
		switch($this->types[$type]){
			case "m-th-sa":
				$last_dow = $last->format("w");
				if($last_dow == 1){
					$last->add(new DateInterval("P3D"));
				} else {
					$last->add(new DateInterval("P2D"));
				}
				break;
			case "monthly-1":
				$month = $last->format("m");
				$last = new DateTime($last->format("Y-".($month+1)."-1 00:00:00"));
				break;
			default:
				$next = '';
				break;
		}
		return $last->format("Y-m-d 00:00:00");
	}

	public function getLastDate($type) {
		$entry = new PaperRoll_Model_Entry();
		$db = $entry->getResource();
		$result = $db->fetchRow($db->select()
			->where("queue IS NULL OR queue = ?", $type)
			->order('published_at DESC')
			->limit(1));
		return $result->published_at;
	}

	public function popQueue() {
		
	}

}
