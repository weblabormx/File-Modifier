<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesSingleRule extends FileActions {

	private $results = array();

	function validation($function) {

		if ( $this->multipleJob( function ( $this ) use ($function) {
			$this->validation($function);
		})) {
			return;
		}
			
		$class = new FileFunctions($this->file);
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