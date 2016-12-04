<?php
namespace WeblaborMX\FileModifier;

class MasiveModifier {

	private static $files;
	private static $directory;
	private static $directories;

	// Constructor

	public static function files(Array $files) {
		self::$files = $files;
		return new self;
	}

	public static function directory($directory) {
		self::$directory = $directory;
		return new self;
	}

	public static function directories(Array $directories) {
		self::$directories = $directories;
		return new self;
	}

	public function execute($function) {
		if(is_array(self::$files) && count(self::$files)>0)
			$this->executeFiles($function);
		if(isset(self::$directory) && strlen(self::$directory)>0)
			$this->executeDirectory($function);
		if(is_array(self::$directories) && count(self::$directories)>0)
			$this->executeDirectories($function);
		return true;
	}

	private function executeEach($function, $file) {
		$fileModifier = FileModifier::file($file);
		$function($fileModifier);
		$fileModifier->execute();
	}

	private function executeFiles($function) {
		foreach (self::$files as $file) {
			$this->executeEach($function, $file);
		}
	}

	private function executeDirectory($function) {
		self::$files = Helper::folder(self::$directory)->files(true);
		$this->executeFiles($function);
	}

	private function executeDirectories($function) {
		foreach (self::$directories as $directory) {
			self::$directory = $directory;
			$this->executeDirectory($function);
		}
	}
}
?>