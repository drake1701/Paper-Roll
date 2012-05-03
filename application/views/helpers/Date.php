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
		$cache = Paper::helper('cache')->getCache();
		if(($result = $cache->load('bannerimage')) === false )
		{
			chdir(APPLICATION_PATH."/../");
			$banners = glob("public/images/banners/*/*.jpg");
			$i = date("j") % count($banners);
			$parts = explode("/", $banners[$i]);
			$result = " {$parts[3]}\" style=\"background-image:url(/{$banners[$i]});";
			$cache->save($result, 'bannerimage');
		}
		return $result;
	}

}
