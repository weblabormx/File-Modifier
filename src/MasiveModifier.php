<?php
namespace WeblaborMX\FileModifier;

class MasiveModifier {

	private static $files;
	private static $directory;
	private static $directories;

	// Constructor

	public static function files(Array $files) 
	{
		self::$files = $files;
		return new self;
	}

	public static function directory($directory) 
	{
		self::$directory = $directory;
		return new self;
	}

	public static function directories(Array $directories) 
	{
		self::$directories = $directories;
		return new self;
	}

	public function execute($function, $execute = true) 
	{
		if(is_array(self::$files) && count(self::$files)>0) {
			return $this->executeFiles($function, $execute);
		}
		if(isset(self::$directory) && strlen(self::$directory)>0) {
			return $this->executeDirectory($function, $execute);
		}
		if(is_array(self::$directories) && count(self::$directories)>0) {
			return $this->executeDirectories($function, $execute);
		}
	}

	private function executeEach($function, $file, $execute) 
	{
		$fileModifier = FileModifier::file($file);
		$function($fileModifier);
		$return = $fileModifier->execute($execute);
		return $return;
	}

	private function executeFiles($function, $execute) 
	{
		$returns = [];
		foreach (self::$files as $file) {
			$returns[] = [
				'return' => $this->executeEach($function, $file, $execute),
				'file' => $file
			];
		}
		return $returns;
	}

	private function executeDirectory($function, $execute) 
	{
		self::$files = Helper::folder(self::$directory)->files(true);
		return $this->executeFiles($function, $execute);
	}

	private function executeDirectories($function, $execute) 
	{
		$returns = [];
		foreach (self::$directories as $directory) {
			self::$directory = $directory;
			$returns[] = $this->executeDirectory($function, $execute);
		}
		return $returns;
	}
}
?>