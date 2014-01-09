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

    public function getNext($date, $type){
        if(is_string($date)){
            $date = new DateTime($date);
        }
        switch($this->types[$type]){
            case "m-th-sa":
                $last_dow = $date->format("w");
                if($last_dow == 1){
                    $date->add(new DateInterval("P3D"));
                } else {
                    $date->add(new DateInterval("P2D"));
                }
                break;
            case "monthly-1":
                $date->add(new DateInterval("P1M"));
                break;
            default:
                break;
        }
        return $date->format("Y-m-d 00:00:00");
    }

	public function getLastQueuedDate($type){
		$last = new DateTime($this->getLastDate($type));
        return $this->getNext($last, $type);
	}

	public function getLastDate($type) {
		$entry = new PaperRoll_Model_Entry();
		$db = $entry->getResource();
		$result = $db->fetchRow($db->select()
			->where("queue = ?", $type)
			->order('published_at DESC')
			->limit(1));
		return $result->published_at;
	}

    public function getLastPublishedDate() {
        $entry = new PaperRoll_Model_Entry();
      		$db = $entry->getResource();
      		$result = $db->fetchRow($db->select()
      			->where("published IS NOT NULL")
      			->order('published_at DESC')
      			->limit(1));
      	return $result->published_at;
    }

	public function getNextQueueEntry() {
		$entry = new PaperRoll_Model_Entry();
		$db = $entry->getResource();
		$result = $db->fetchRow($db->select()
			->where("published IS NULL")
			->where("published_at < NOW()")
			->order('published_at ASC')
			->limit(1));
		if(count($result)){
			return $entry->load($result->id);
		}
		return false;
	}

	public function popQueue() {
		while($entry = $this->getNextQueueEntry()){
			$entry->setData('published', 1);
			$entry->save();
			Paper::log($entry->getData('title'));
		}
	}

    public function reorder(Array $ids, $type) {
        $last = $this->getLastPublishedDate();
        $next = $this->getNext($last, $type);
        foreach($ids as $id){
            $entry = new PaperRoll_Model_Entry();
            $entry->load($id);
            if($entry->getData('published_at') != $next){
                $entry->setData('published_at', $next)->save();
            }
            $next = $this->getNext($next, $type);
        }
    }

}
