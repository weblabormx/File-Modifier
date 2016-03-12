<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesSingleRuleValidation {

	private $file;
	private $results = array();

	function __construct($file) {
		$this->file = $file;
	}

	function whereSearch($search, $count=null) {
		$execution = FileModifier::file($this->file)->find($search)->execute();
		$result = false;
		if (isset($execution[$search])) {
			$execution = $execution[$search];
			if (is_numeric($count)) {
				$result = count($execution) == $count;
			} else if (count($execution) > 0) {
				$result = true;
			}
		}
		$this->results[] = $result;
	}

	function results() {
		if (count(array_unique($this->results)) === 1 && end($this->results) === true) {
			return true;
		}
		return false;
	}
}
?>