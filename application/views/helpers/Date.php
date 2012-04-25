<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_View_Helper_Date extends Zend_Date
{
	protected $_bannerImage;

	/*
	 * @return string
	 */
	public function format($date, $short = false)
	{
		$format = $short ? "M jS, 'y" : "l, M jS, Y";
		return date($format, strtotime($date));
	}

	public function getBannerImage()
	{
		if(!isset($this->_bannerImage))
		{
			chdir(APPLICATION_PATH."/../");
			$banners = glob("public/images/banners/*/*.jpg");
			$i = date("j") % count($banners);
			$parts = explode("/", $banners[$i]);
			$this->_bannerImage = " {$parts[3]}\" style=\"background-image:url(/{$banners[$i]});";
		}
		return $this->_bannerImage;
	}

}
