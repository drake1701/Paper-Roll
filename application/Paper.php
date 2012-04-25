<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class Paper extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initDoctype()
	{
		$this->bootstrap('view');
        	$view = $this->getResource('view');
        	$view->doctype('XHTML1_STRICT');
	}
	
	static public function log($message)
	{	
		if(!is_dir(APPLICATION_PATH . '/../logs/')){
			mkdir(APPLICATION_PATH . '/../logs/');			
		}
		$stream = fopen(APPLICATION_PATH . '/../logs/system.log', 'a', false);
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
		$helper = "PaperRoll_View_Helper_$name";
		return new $helper;
	}
}

