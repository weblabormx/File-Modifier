<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesSingleRule {

	private $results = array();
	private $directory;
	private $file;
	private $searchFor = array();
	private $searchIn = array();
	private $multiple = false;

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

	function validation($function) {
		if ($this->multiple) {
			$this->multiple = false;
			$class = new AnalyzeFilesGetFiles;
			$file = $class->getFiles($this->directory, $this->searchFor, $this->searchIn);

			$copyfiles = $file;
			foreach ($copyfiles as $fileg) {
				foreach ($fileg as $file) {
					$file = str_replace('\\', '/', $file);
					$this->file = $file;
					$this->validation($function);
				}
			}
			return;
		}
			
		$class = new AnalyzeFilesSingleRuleValidation($this->file);
		$function($class);
		$file = $this->file;
		if (!is_array($this->directory)) {
			$file = str_replace($this->directory.'/', '', $this->file);
		}
		$this->results[$file] = $class->results();
	}

	function getResults() {
		return $this->results;
	}

}
?>