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
    	$this->view->title = $entry->getData('title')." | ";
    	$this->view->entry = $entry;
    }
}