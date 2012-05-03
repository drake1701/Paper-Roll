<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class AdminController extends Zend_Controller_Action
{

    public function preDispatch()
    {
		if (Zend_Auth::getInstance()->hasIdentity() == false) {
            // If they aren't, they can't logout, so that action should 
            // redirect to the login form
            if (!preg_match("#(login|loginprocess)#", $this->getRequest()->getActionName())) {
                $this->_helper->redirector('login');
            }
        }
    }
     
    public function loginAction()
    {
        $this->view->form = $this->getForm();
    }

	public function getForm()
	{
		return new PaperRoll_View_Helper_LoginForm(array(
			'action' => '/admin/loginProcess',
			'method' => 'post',
		));
	}

	public function indexAction()
	{
		$entry = new PaperRoll_Model_Entry();
		$this->view->queue = $entry->getQueue();
	}

	public function newAction()
	{
		chdir(APPLICATION_PATH . '/../public/gallery/thumb/');
		$files = glob('*.jpg');
		$image = new PaperRoll_Model_Image();
		$images = $image->getThumbImages();
		$this->view->images = array_diff($files, $images);
	}

	public function newprocessAction()
	{
		$this->view->image = $this->getRequest()->getParam('image');
		$this->_forward('edit');
	}

	public function editAction()
	{
		if($this->getRequest()->getParam('id')){
			$entry = new PaperRoll_Model_Entry();
			$entry->load($this->getRequest()->getParam('id'));
			$this->view->entry = $entry;
		}
	}

	public function saveAction()
	{
		$data = $this->getRequest()->getPost();
		$entry = new PaperRoll_Model_Entry();
		if(isset($data['id']))
			$entry->load($data['id']);

		$entry->setData($data);
		$entry->save();
		if(isset($data['tags']) && $data['tags'] != ''){
			$slugs = explode(",", $data['tags']);
			$tag = new PaperRoll_Model_Tag();
			$tag->checkLinks($entry->getId(), $slugs);
		}
        return $this->_helper->redirector->gotoSimple('index', 'admin');
	}

	public function deleteAction()
	{
		if($this->getRequest()->getParam('id')){
			$entry = new PaperRoll_Model_Entry();
			$entry->load($this->getRequest()->getParam('id'));
			$entry->delete();
		}
        return $this->_helper->redirector->gotoSimple('index', 'admin');
	}

	public function getAuthAdapter(array $params)
	{
		return new Zend_Auth_Adapter_Digest(APPLICATION_PATH . "/configs/admin.ini", "Admin Area", $params['username'], $params['password']);		
	}
	
	public function loginprocessAction()
    {
        $request = $this->getRequest();
        // Check if we have a POST request
        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // Get our form and validate it
        $form = $this->getForm();
        if (!$form->isValid($request->getPost())) {
            // Invalid entries
            $this->view->form = $form;
            return $this->render('login'); // re-render the login form
        }

        // Get our authentication adapter and check credentials
        $adapter = $this->getAuthAdapter($form->getValues());
        $auth    = Zend_Auth::getInstance();

        $result  = $auth->authenticate($adapter);
        
        if (!$result->isValid()) {
            // Invalid credentials
            $form->setDescription('Invalid credentials provided');
            $this->view->form = $form;
            return $this->render('login'); // re-render the login form
        }

        // We're authenticated! Redirect to the home page
        $this->_helper->redirector('index');
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login'); // back to login page
    }

}