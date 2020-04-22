<?php
namespace WeblaborMX\FileModifier;

class Helper {

	public static function startsWith( $haystack, $needle ) 
	{
	    // search backwards starting from haystack length characters from the end
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	public static function endsWith( $haystack, $needle ) 
	{
	    // search forward starting from end minus needle length characters
	    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}

	public static function hasString($string, $search) 
	{
		if (!is_array($search)) {
			$search= array($search);
		}
		foreach ($search as $value) {
			$pos1 = strpos($string, $value);
			if ( ($pos1 !== false) ) {
				return true;
			}
		}
		return false;
	}

	public static function folder( $name ) 
	{
	    return new Folder($name);
	}
	
}
?>