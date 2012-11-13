<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_View_Helper_Cache {

	protected function _getCacheDir()
	{
		$cacheDir = APPLICATION_PATH . '/../var/cache/';
		if(!is_dir($cacheDir)){
			mkdir($cacheDir, 0777, true);
		}
		return $cacheDir;
	}

	public function getCache() {
		$front = array(
			'lifetime' => 7200, // 2 hours
			'automatic_serialization' => true
		);
		$back = array(
			'cache_dir' => $this->_getCacheDir()
		);
		$cache = Zend_Cache::factory('Core', 'File', $front, $back);
		if(APPLICATION_ENV != 'production') {
			$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		}
		return $cache;
	}

	public function getPageCache() {
		$front = array(
			'lifetime' => 7200, // 2 hours
			'debug_header' => true
		);
		$back = array(
			'cache_dir' => $this->_getCacheDir()
		);
		$cache = Zend_Cache::factory('Page', 'File', $front, $back);
		if(false && APPLICATION_ENV != 'production') {
			$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		}
		return $cache;
	}

}
