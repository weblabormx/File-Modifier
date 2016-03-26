<?php
namespace WeblaborMX\FileModifier;

class FileRunnerActions {
	
	public $line;
	public $value;
	private $action;

	function __construct($value, $action, $line) {
		$this->value = $value; 	// data of the line
		$this->line = $line; 	// Number of line
		$this->action = $action;
	}

	function find($search) {
		$seach = trim($search);
		if (Helper::hasString( $this->value, $search )) {
			return true;
		}
		return false;
	}

	function findByLine($line) {
		if ($this->line == $line) {
			return true;
		}
		return false;
	}

	function replace($search, $replace) {
		if (Helper::hasString( $this->value, $search )) {
			$this->value = str_replace($search, $replace, $this->value);
			return true;
		}
		return false;
	}

	function replaceLineWhere($search, $replace) {
		if (Helper::hasString( $this->value, $search )) {
			$this->value = $replace;
			return true;
		}
		return false;
	}

	function addBeforeLine($search, $addition) {
		if (Helper::hasString( $this->value, $search )) {
			$this->value = "$addition\n$this->value";
			return true;
		}
		return false;
	}

	function addAfterLine($search, $addition) {
		if (Helper::hasString( $this->value, $search )) {
			$this->value = "$this->value$addition\n";
			return true;
		}
		return false;
	}

	function addBeforeLineByLine($line, $addition) {
		if ($this->line == $line) {
			$this->value = "$addition\n$this->value";
			return true;
		}
		return false;
	}

	function addAfterLineByLine($line, $addition) {
		if ($this->line == $line) {
			$this->value = "$this->value$addition\n";
			return true;
		}
		return false;
	}

	function changeLine($line, $change) {
		if ($this->line == $line) {
			$this->value = "$change\n";
			return true;
		}
		return false;
	}

	function getValue() {
		return $this->value;
	}

}
?>