<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesRules {

	private static $directory; 
	private static $pointer; 
	private static $results = array();

	function __construct() {
		self::$results = array();
	}
	
	static function directory($directory) {
		self::$directory = $directory;
		self::$pointer = new self;
		return self::$pointer;
	}

	static function create($function) {
		$function(self::$pointer);
		return self::finish();
	}

	static function add($function) {
		$singlerule = new AnalyzeFilesSingleRule(self::$directory);
		$function($singlerule);
		self::$results = array_merge(self::$results, $singlerule->getResults());
	}

	static function finish() {
		return self::$results;
	}

}
?>