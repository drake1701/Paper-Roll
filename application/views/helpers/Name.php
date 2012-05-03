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
		if(preg_match("#-[ivxlm]*\$#", $code)){
			$parts = explode("-", $code);
			$roman = strtoupper(array_pop($parts));
			array_push($parts, $roman);
			$code = implode("-", $parts);
		}
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
