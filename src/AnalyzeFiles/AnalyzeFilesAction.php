<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesAction extends FileFunctions {

	private $execute = true;
	private $finalResults = array();

	public function __call($name, $arguments) {	

		if ($this->results()) { // If the requirements pass
			
			if (count($arguments)==1) {
	    		$res = FileModifier::file($this->file)->$name($arguments[0])->execute($this->execute);
	    	} else if (count($arguments)==2) {
	    		$res = FileModifier::file($this->file)->$name($arguments[0], $arguments[1])->execute($this->execute);
	    	}
	    	$this->finalResults = array_merge($this->finalResults, $res);

		}

    }

    function setExecute($execute) {
		$this->execute = $execute;
	}

    function allResults() {
    	return $this->finalResults;
    }

}
?>