<?php
namespace WeblaborMX\FileModifier;

class FileFunctions {

	public $file;
	public $results = array();
	public $validation = array();

	function __construct($file) {
		$this->file = $file;
	}

	function addValidation($function, $value, $result) {
		$this->validation[] = array(
			'function'	=> $function,
			'value'		=> $value,
			'result'	=> $result
		);
	}

	function getValidation() {
		return $this->validation;
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
		$this->addValidation('whereSearch',$search,$result);
	}

	function whereNoSearch($search, $count=null) {
		$execution = FileModifier::file($this->file)->find($search)->execute();
		$result = true;
		if (isset($execution[$search])) {
			$execution = $execution[$search];
			if (is_numeric($count)) {
				$result = count($execution) != $count;
			} else if (count($execution) > 0) {
				$result = false;
			}
		}
		$this->results[] = $result;
		$this->addValidation('whereNoSearch',$search,$result);
	}

	function results() {
		if (count(array_unique($this->results)) === 1 && end($this->results) === true) {
			return true;
		}
		return false;
	}

}
?>