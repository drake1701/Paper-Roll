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
			mkdir($logDir);
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

    protected function _initCache()
    {
        $dir = $this::helper('Cache')->getCacheDir();

        $frontendOptions = array(
            'lifetime' => 86400,
            'default_options' => array(
                'cache' => false,
            ),
            'regexps' => array(
                '^/$' => array('cache' => true),
                '^/page/tags' => array('cache' => true),
                '^/admin' => array('cache' => false),
            )
        );

        $backendOptions = array(
            'cache_dir' =>$dir
        );

        // getting a Zend_Cache_Frontend_Page object
        $cache = Zend_Cache::factory('Page',
            'File',
            $frontendOptions,
            $backendOptions
        );
        $cache->start(md5($_SERVER['REQUEST_URI']));
    }
}

