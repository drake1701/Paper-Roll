<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_View_Helper_Date extends Zend_Date
{
	/*
	 * @return string
	 */
	public function format($date)
	{
		return date("l, M jS, Y", strtotime($date));
	}

}
