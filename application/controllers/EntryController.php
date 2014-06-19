<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */
 
class EntryController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('entry/list');
    }

    public function listAction()
    {
        $entry = new PaperRoll_Model_Entry();
        $this->view->entries = $entry->getVisible();
    }
    
    public function viewAction()
    {
    	$entry = new PaperRoll_Model_Entry();
    	if($this->getRequest()->getParam('e') == null){
    		$this->_redirect('entry');
    	}
    	$entry->load((int)$this->getRequest()->getParam('e'));
    	$this->view->canonical = Paper::getBaseUrl() . "/" .$entry->getUrlPath();
    	$this->view->title = $entry->getData('title')." | ";
    	$this->view->entry = $entry;
    	
    	$head = '
    	<meta name="twitter:card" content="photo">
        <meta name="twitter:site" content="@spartacuswalls">
        <meta name="twitter:creator" content="@spartacuswalls">
        <meta name="twitter:title" content="'.$entry->getData('title').'">
        <meta name="twitter:description" content="'.$entry->getData("description").'">
        <meta name="twitter:image:src" content="'.$entry->getImageUrl('preview').'">
        
        <meta property="og:type" content="article" />
        <meta property="og:title" content="'.$entry->getData('title').'" />
        <meta property="og:site_name" content="Spartacus Wallpaper"/>
        <meta property="og:url" content="'.Paper::getBaseUrl() . "/" .$entry->getUrlPath().'" />
        <meta property="og:description" content="'.$entry->getData("description").'" />
        <meta property="og:image" content="'.$entry->getImageUrl('preview').'" />
        <meta property="og:updated_time" content="'.$entry->getData('published_at').'" />
        ';
        $this->view->head = $head;
    }
}