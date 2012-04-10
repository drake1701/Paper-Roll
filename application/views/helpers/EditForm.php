<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class PaperRoll_View_Helper_EditForm extends Zend_Form
{
    public function init()
    {
        $username = $this->addElement('text', 'title', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'title:',
        ));

		$content = $this->addElement('textarea', 'content', array(
			'label'		=> 'content:'
		));

		$queue = $this->addElement('select', 'queue', array(
			'label'		=> 'queue:',
			'multiOptions'	=> array(
				'1' => "Normal",
				'2' => "Calendar"
			)
		));

        $login = $this->addElement('submit', 'save', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'save',
        ));

        // We want to display a 'failed authentication' message if necessary;
        // we'll do that with the form 'description', so we need to add that
        // decorator.
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }
}