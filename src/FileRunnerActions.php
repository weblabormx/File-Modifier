<?php
namespace WeblaborMX\FileModifier;

class FileRunnerActions 
{
	
	public $line;
	public $value;
	private $action;
	private $pos;
	private $posSuccessfull = false;

	public function __construct($value, $action, $line, $pos) 
	{
		$this->value = $value; 	// data of the line
		$this->line = $line; 	// Number of line
		$this->action = $action;
		$this->pos = $pos;
	}

	public function find($search) 
	{
		if ($this->generalFilter(Helper::hasString( $this->value, $search ))) {
			return true;
		}
		return false;
	}

	public function findAtBeginning($search) 
	{
		if ($this->generalFilter(Helper::startsWith( $this->value, $search ))) {
			return true;
		}
		return false;
	}

	public function getLine($line) 
	{
		if ($this->generalFilter($this->line == $line)) {
			return true;
		}
		return false;
	}

	public function replace($search, $replace) 
	{
		if ($this->generalFilter(Helper::hasString( $this->value, $search ))) {
			$this->value = str_replace($search, $replace, $this->value);
			return true;
		}
		return false;
	}

	public function replaceLineWhere($search, $replace) 
	{
		if ($this->generalFilter(Helper::hasString( $this->value, $search ))) {
			$this->value = $replace;
			return true;
		}
		return false;
	}

	public function addBeforeLine($search, $addition) 
	{
		if ($this->generalFilter(Helper::hasString( $this->value, $search ))) {
			$this->value = "$addition\n$this->value";
			return true;
		}
		return false;
	}

	public function addAfterLine($search, $addition) 
	{
		if ($this->generalFilter(Helper::hasString( $this->value, $search ))) {
			$this->value = "$this->value$addition\n";
			return true;
		}
		return false;
	}

	public function addBeforeLineByLine($line, $addition) 
	{
		if ($this->generalFilter($this->line == $line)) {
			$this->value = "$addition\n$this->value";
			return true;
		}
		return false;
	}

	public function addAfterLineByLine($line, $addition) 
	{
		if ($this->generalFilter($this->line == $line)) {
			$this->value = "$this->value$addition\n";
			return true;
		}
		return false;
	}

	public function addAfterLineByLineNoN($line, $addition) 
	{
		if ($this->generalFilter($this->line == $line)) {
			$this->value = "$this->value$addition";
			return true;
		}
		return false;
	}

	public function changeLine($line, $change) 
	{
		if ($this->generalFilter($this->line == $line)) {
			$this->value = "$change\n";
			return true;
		}
		return false;
	}

	public function removeLine($line) 
	{
		if ($this->generalFilter($this->line == $line)) {
			$this->value = "";
			return true;
		}
		return false;
	}

	public function removeLineWhere($search) 
	{
		if ($this->generalFilter(Helper::hasString( $this->value, $search ))) {
			$this->value = "";
			return true;
		}
		return false;
	}

	public function getValue() 
	{
		return $this->value;
	}

	public function getPosSuccessfull() 
	{
		return $this->posSuccessfull;
	}

	public function generalFilter($resultAction) 
	{
		$return = true;
		if (
			$this->action['lines'] && 
			isset($this->action['lines']['starts']) && 
			isset($this->action['lines']['finish'])
		) {
			if (
				$this->line < $this->action['lines']['starts']
				|| $this->line > $this->action['lines']['finish']
			) {
				$return = false;
			}
		}
		if ($return && $this->action['pos'] && $this->pos != $this->action['pos']) {
			// if were ok if we didn't have pos option
			if ($resultAction) {
				$this->posSuccessfull = true;
			}
			$return = false;

		}
		if ($return && !$resultAction) {
			$return = false;
		}

		return $return;
	}
}
?>