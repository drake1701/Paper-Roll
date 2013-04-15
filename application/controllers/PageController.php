<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */
 
class PageController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->redirector('index', 'index');
    }

    public function tagsAction()
    {
    	$this->_forward("showall", "tag");
    }

	public function aboutAction() {
    	$this->view->title = "About | ";
    }

	public function contactAction() {
    	$this->view->title = "Contact Me | ";
    }

	public function donateAction() {
    	$this->view->title = "Donate | ";
    }

}