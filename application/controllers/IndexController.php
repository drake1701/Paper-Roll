<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        if(MOBILE == true){
            $this->_forward("index", "mobile");
        }
		$entry = new PaperRoll_Model_Entry();
    	$this->view->entries = $entry->getLatest(10);
    	$this->view->title = "Female Celebrity Wallpaper | ";
    }


}

