<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class Paper extends Zend_Application_Bootstrap_Bootstrap
{
	protected $cache;

	protected function _initDoctype()
	{
		$this->bootstrap('view');
        	$view = $this->getResource('view');
        	$view->doctype('XHTML1_STRICT');
	}
	
	static public function log($message)
	{
		$logDir = APPLICATION_PATH . '/../var/log/';
		if(!is_dir($logDir)){
			mkdir($logDir, 0777, true);
		}
		$stream = fopen("{$logDir}system.log", 'a', false);
		if (! $stream) {
		    throw new Exception('Failed to open stream');
		}
		
		$writer = new Zend_Log_Writer_Stream($stream);
		$logger = new Zend_Log($writer);

	    if (is_array($message) || is_object($message)) {
	        $message = print_r($message, true);
	    }
            		
    	$logger->info($message);
	}

	static public function helper($name)
	{
		$name = ucwords($name);
		$helper = "PaperRoll_View_Helper_$name";
		return new $helper;
	}

	public function getCache()
	{
		if($this->cache == null) {
			$this->cache = $this::helper('Cache')->getCache();
		}
		return $this->cache;
	}


}

