<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesActions extends FileActions {

	private $results = array();
	private $execute = true;

	function action($function) {

		if ( $this->multipleJob( function ( $this ) use ($function) {
			$this->action($function);
		})) {
			return;
		}

		$class = new AnalyzeFilesAction($this->file);
		$class->setExecute($this->execute);
		$function($class);
		$file = $this->file;

		if (!is_array($this->directory)) {
			$file = str_replace($this->directory.'/', '', $this->file);
		}

		$this->results[$file][] = array(
			'result'		=> $class->results(),
			'requirements'	=> $class->getValidation(),
			'results'		=> $class->allResults()
		);

	}

	function setExecute($execute) {
		$this->execute = $execute;
	}

	function getResults() {

		return $this->results;

	}

}
?>