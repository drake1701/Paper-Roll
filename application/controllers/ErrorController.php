<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        $rewrite = $this->fetchRewrite($this->getRequest()->getRequestUri());
        if($rewrite){
        	$this->_forward($rewrite['action'], $rewrite['controller'], $rewrite['module'], $rewrite['params']);
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
    
    public function fetchRewrite($path)
    {
    	// handle paths with date "folders"
    	$path_parts = explode("/", $path);

		if(count($path_parts) > 2 AND $path_parts[1] == 'tag'){
			return array('action' => 'list', 'controller' => 'tag', 'module' => null, 'params' => array('tag' => $path_parts[2]));
		}

    	if(count($path_parts) > 2 AND strpos($path, ".html")){
    		// current db has no paths where final file is identical, so send old requests to simpler paths with 301s
    		$this->_redirect(array_pop($path_parts), array('code' => 301));
    	}
    	$key = array_pop($path_parts);
    	    	
    	$entry = new PaperRoll_Model_Entry();
    	$table = $entry->getMapper()->getDbTable();
    	$row = $table->fetchRow(
    		$table->select()
    			->where('url_path = ?', $key)	
    	);
    	if($row && $row->id > 0){
	    	return array('action' => 'view', 'controller' => 'entry', 'module' => null, 'params' => array('e' => $row->id));    	
    	} else {
    		return array('action' => 'index', 'controller' => 'index', 'module' => null, 'params' => null);
    	}
    	
    }


}

