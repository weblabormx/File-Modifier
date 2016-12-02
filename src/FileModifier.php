<?php
namespace WeblaborMX\FileModifier;

class FileModifier {
	static private $file;
	static private $pointer;
	private $actions = array();

	static function file($file) {
		self::$file = $file;
		self::$pointer = new self;
		return self::$pointer;
	}

	public function __call($name, $arguments) {

    	$res 				= array();
		$res["search"] 		= $arguments[0];
		$res["function"] 	= $name;
		$res["val"] 		= isset($arguments[1]) ? $arguments[1] : false;
		$res["lines"] 		= isset($arguments[2]['lines']) ? $arguments[2]['lines'] : false;
		$res["pos"] 		= isset($arguments[2]['pos']) ? $arguments[2]['pos'] : false;
		
		$this->actions[] = $res;
		return self::$pointer;

    }

    // Basic functions

	function exists() {
		return file_exists(self::$file);
	}

	function create($data) {
		if($fh = fopen(self::$file,'w')){
		    $stringData = $data;
		    fwrite($fh, $stringData, 1024);
		    fclose($fh);
		}
	}

	function count() {
		if (count($this->actions)==0) { // We are analyzing the file
			$class = FileRunner::file(self::$file);
			$class->actions($this->actions, false);
			return $class->getNumlines();
		}
		$do = FileRunner::file(self::$file)->actions($this->actions, false);
		$do = !$do ? 0 : count($do); // If the result is false don't count
		return $do;
	}

	function execute($real = true) {
		return FileRunner::file(self::$file)->actions($this->actions, $real);
	}

	function first($real = true) {
		$objects = $this->execute($real);
		if ($objects) {
			foreach ($objects as $object) {
				return $object;
			}
		}
		return false;
	}

	// General functions
	function removeLinesWhere($start_keyword, $finish_keyword) {
		$start = FileModifier::file(self::$file)->find($start_keyword)->first()->line;
		$finish = FileModifier::file(self::$file)->find($finish_keyword)->first()->line;
		return $this->removeLinesBetweenLines($start, $finish);
	}

	function removeLinesBetweenLines($start, $finish) {
		$FileModifier = FileModifier::file(self::$file);
		for ($i=$start; $i <= $finish; $i++) { 
			$FileModifier = $FileModifier->removeLine($i);
		}
		return $FileModifier;
	}

	// Advance functions
	function getFunctionLines($function, $array = array()) {
		$search = "function $function";
		$res = $this->find($search, false, $array)->first();
		if ($res!==false) {
			$numline = $res->line;
			$line = $this->getLine($numline)->first()->value;

			$firstText = explode(' ', trim($line)); // Get the first text of the sentence
    		$spac = explode($firstText[0], $line); // Get the space between first word and the begining
			$spac = $spac[0];

			$array = array('lines'=>array('starts' => $numline, 'finish' => $numline+1000));
			$this->actions = array();
			$finishLine = $this->findAtBeginning($spac.'}', false, $array)->first()->line;
			$res = array("starts"=>$numline, "finish"=>$finishLine);
			return $res;
		}
		return false;
	}

	function getFunctionInit($function, $array = array()) {
		$search = array("function $function(","function $function (");
		$res = $this->find($search, false, $array)->execute();
		if ($res==false) {
			return false;
		}
		foreach ($res as $value) {
			foreach ($value as $object) {
				$line = $object->line;
			}
		}
		if (!isset($line)) {
			return false;
		}
		//echo $line;
		$searchstart = $line-50;

		$archivo = self::$file;
		$handle = fopen($archivo, "r");
		if ($handle) { // process the line read.
			$arrayR = array();
			$resF = 0;
			$numline = 0;
		    while (($liness = fgets($handle)) !== false) {
		    	$numline++;
		    	if ($numline>$line) {
		    		break;
		    	}
		    	if ($numline>$searchstart) {
		    		# Start searching
		    		$arrayR[$numline] = trim($liness);
		    	}
		    }
		    $arrayR = array_reverse($arrayR, true);
		    $pas1 = false;
		    foreach ($arrayR as $key => $value) {
		    	if ($key==$line) {
		    		continue;
		    	}
		    	//echo "[$key] => $value\n";
		    	$value = trim($value);
		    	if (strlen($value)<=0) {
		    		continue;
		    	}
		    	if (Helper::startsWith($value, "//")) {
		    		continue;
		    	}
		    	if ($pas1==false) {
		    		$pos1 = strpos($value, "*/");
		    		$pas1 = true;
			    	if($pos1!==false) {
			    		//echo "Entra";
			    		//$resF = $key+1;
			    		continue;
			    	}
			    	$resF = $key+1;
			    	break;
		    	} else if($pas1==true) {
		    		$pos1 = strpos($value, "/*");
				    if($pos1!==false) {
				    	//echo "Entra 2";
				    	$resF = $key;
				    	break;
				    }
				    continue;
		    	}
			    	
			    
		    	
		    }
		    return $resF;
		}
		return false;
	}

	function getIfLines($if, $array = array()) {
		$search = array($if);
		$res = $this->find($search, false, $array)->first();
		if ($res==false) {
			return false;
		}
		$line = $res->line;
		if (isset($line)) {
			$archivo = self::$file;
			$handle = fopen($archivo, "r");
			if ($handle) { // process the line read.
				$arrayR = array();
				$resF = 0;
				$numline = 0;
			    while (($liness = fgets($handle)) !== false) {
			    	$numline++;
			    	if ($numline==$line) {
			    		$spac = explode($if, $liness);
						$spac = $spac[0];
			    	}
			    	if ($numline<$line) {
			    		continue;
			    	}
		    		//echo "[$numline] => $liness\n";
			    	if (Helper::startsWith($liness, "$spac}")) {
			    		$finishLine = $numline;
			    		//echo "Entra";
			    		break;
			    	}
			    }
			    if (!isset($finishLine)) {
			    	return array("starts"=>0, "finish"=>0);
			    }
			    $res = array("starts"=>$line, "finish"=>$finishLine);
			    return $res;
			}
		}
		return array("starts"=>0, "finish"=>0);
	}
}
?>