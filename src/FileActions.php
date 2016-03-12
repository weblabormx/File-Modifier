<?php
namespace WeblaborMX\FileModifier;

abstract class FileActions {

	public $directory;
	public $file;
	public $searchFor = array();
	public $searchIn = array();
	public $multiple = false;

	function __construct($directory) {

		$this->directory = $directory;

	}
	
	function files($array) {

		$this->searchFor = $array;
		$this->multiple = true;

	}

	function fileIs($name) {

		$this->file = $this->directory.'/'.$name;

	}

	function fileEndsWith($termination) {

		$this->searchFor = $termination;
		$this->multiple = true;

	}

	function filesInside($array) {

		$this->searchIn = $array;

	}

	function multipleJob($function2) {

		if ($this->multiple) {
			$this->multiple = false;
			$class = new FileSearcher;
			$file = $class->getFiles($this->directory, $this->searchFor, $this->searchIn);

			$copyfiles = $file;
			foreach ($copyfiles as $fileg) {
				foreach ($fileg as $file) {
					$file = str_replace('\\', '/', $file);
					$this->file = $file;
					$function2($this);
				}
			}
			return true;
		}
		return false;

	}
}
?>