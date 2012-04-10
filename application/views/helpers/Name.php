<?php
/**
 * @package	PaperRoll
 * @author 	dennis
 * @site 	www.drogers.net
 */
 
class PaperRoll_View_Helper_Name {

	/*
	 * @returns string
	 */
	public function codeToName($code)
	{
		$code = $this->stripRoman($code);
		return trim(ucwords(preg_replace("#(-|_)#", " ", $code)));
	}

	/*
	 * @returns string
	 */
	public function fileToName($file)
	{
		return $this->codeToName(str_replace(".jpg", '', strtolower($file)));
	}

	/*
	 * @returns string
	 */
	public function stripRoman($string)
	{
		return preg_replace("#-[ivxlm]*\$#", "", $string);
	}
}
