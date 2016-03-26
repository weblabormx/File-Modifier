<?php
namespace WeblaborMX\FileModifier;

class FileRunner {

	static private $file;
	private $res = array();
	private $numline = 0;
	private $searchedWords = array();
	private $real; 
	private $actions;

	static function file($file) {
		self::$file = $file;
		return new self;
	}

	function actions($actions, $real) {
		$this->actions = $actions;
		$this->real = $real;
		$archivo = self::$file;
		$handle = fopen($archivo, "r");
		$text_file = '';
		if ($handle) { // process the line read.
		    while (($line = fgets($handle)) !== false) {
				$this->numline++;
		    	$text_file .= $this->line($line);
		    }
		    fclose($handle);

		    if($this->real) { // Make changes if is real only
    			file_put_contents($archivo, $text_file);
    		}
		    if (count($this->res)==0) {
		    	return false;
		    }
		    return $this->res;
		}
		return false;
	}

	function line($line) {
		$oldLine = $line;

		foreach ($this->actions as $action) {

			if (!is_array($action["search"])) {
	    		$action["search"] = array($action["search"]);
	    	} 

	    	$function = $action['function'];

	    	foreach ($action["search"] as $search) {

	    		$actionc = $action;
	    		$actionc['search'] = $search;
	    		$FileRunnerActions = new FileRunnerActions($line, $actionc, $this->numline);

	    		if (strlen($search)<=0) {
    				continue;
    			}

	    		$this->searchedWords[] = $search;

	    		if($action['val']) {
					$success = $FileRunnerActions->$function($search, $action['val']);
	    		} else {
	    			$success = $FileRunnerActions->$function($search);
	    		}
	    		
	    		if ($success) { // If successfull
	    			if (count($action["search"]) == 1) { // If is single search
	    				$this->res[] = $FileRunnerActions;
	    			} else { // if has multiple search
	    				$this->res[$search][] = $FileRunnerActions;
	    			}
	    			
	    		}

	    		$line = $FileRunnerActions->getValue();
    		}
		}

		return $line;
	}

	function getNumlines() {
		return $this->numline;
	}
}
?>