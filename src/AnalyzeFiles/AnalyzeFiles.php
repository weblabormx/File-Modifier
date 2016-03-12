<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFiles {

	private static $directory; 
	private static $pointer; 
	private static $results = array();

	static function directory($directory) {
		self::$directory = $directory;
		self::$pointer = new self;
		return self::$pointer;
	}

	static function rules() {
		$rules = new AnalyzeFilesRules;
		return $rules;
	}

	static function execute($function, $execute=true) {
		$class = new AnalyzeFilesActions(self::$directory);
		$class->setExecute($execute);
		$function($class);
		self::$results = array_merge(self::$results, $class->getResults());
		return self::$results;
	}

	

}
?>