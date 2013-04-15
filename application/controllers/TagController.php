<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */
 
class TagController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->redirector('index', 'index');
    }

    public function listAction()
    {
    	if($this->getRequest()->getParam('tag') == null){
    		$this->_helper->redirector('index');
    	}
        $tag = new PaperRoll_Model_Tag();
        $this->view->entries = $tag->getEntries($this->getRequest()->getParam('tag'));
		if(count($this->view->entries) == 1){
			$entry = array_pop($this->view->entries);
			$this->_redirect($entry->getUrlPath());
		}
    	$this->view->title = $tag->loadBySlug($this->getRequest()->getParam('tag'))->getData('title')." | ";
    }

	public function showallAction()
	{
        $tag = new PaperRoll_Model_Tag();
        $this->view->tags = $tag->getIndex();
    	$this->view->title = "Tag Index | ";
	}
}