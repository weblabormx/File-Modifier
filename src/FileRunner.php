<?php
namespace WeblaborMX\FileModifier;

class FileRunner 
{
	static private $file;
	private $res = array();
	private $numline = 0;
	private $real; 
	private $actions;
	private $pos;

	static function file($file) 
	{
		self::$file = $file;
		return new self;
	}

	public function actions($actions, $real) 
	{
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

	public function line($line) 
	{
		$oldLine = $line;

		foreach ($this->actions as $action) {

			if (!is_array($action["search"])) {
	    		$action["search"] = array($action["search"]);
	    	} 

	    	$function = $action['function'];

	    	foreach ($action["search"] as $search) {

	    		$pos = isset($this->pos[$action['function']][$search]) ? count($this->pos[$action['function']][$search]) : 0;
	    		$pos++;
	    		$actionc = $action;
	    		$actionc['search'] = $search;
	    		$FileRunnerActions = new FileRunnerActions($line, $actionc, $this->numline, $pos);

	    		if (strlen($search)<=0) {
    				continue;
    			}

	    		if($action['val']!==false) {
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

	    		if ($FileRunnerActions->getPosSuccessfull()) {
	    			$this->pos[$action['function']][$search][] = true;
	    		}

	    		$line = $FileRunnerActions->getValue();
    		}
		}

		return $line;
	}

	public function getNumlines() 
	{
		return $this->numline;
	}
}
?>