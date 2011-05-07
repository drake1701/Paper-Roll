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
        $entry = new PaperRoll_Model_Entry();
        $this->view->entries = $entry->fetchAll();
    }
}