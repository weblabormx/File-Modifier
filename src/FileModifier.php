<?php
namespace WeblaborMX\FileModifier;

class FileModifier {
	static private $file;
	static private $wait = true;
	static private $waitFunctions;
	static private $madeChanges;
	static private $searchedWords;
	private static $pointer;

	static function file($file) {
		self::$file = $file;
		self::$wait = true;
		self::$waitFunctions = array();
		self::$madeChanges = true;
		self::$searchedWords = array();
		self::$pointer = new self;
		return self::$pointer;
	}

	function findAndReplace($search, $type, $val2 = "", $asincrono=false, $lines=false, $pos=false) {
		self::$searchedWords = array();
		if ($asincrono==false) {
			$asincrono = array();	
			$res1 = array();
			$res1["val1"] = $search;
			$res1["val2"] = $type;
			$res1["val3"] = $val2;
			$res1["val5"] = $lines;
			$res1["val6"] = $pos;
			$asincrono[] = $res1;
		}
		//var_dump($asincrono);
		$res = array();
		$resPas = array();
		$archivo = self::$file;
		$text_file = '';
		$bandera = false;
		$handle = fopen($archivo, "r");
		if ($handle) { // process the line read.
			$numline = 0;
		    while (($line = fgets($handle)) !== false) {
				$numline++;
		    	foreach ($asincrono as $value3) {
	    			$search = $value3["val1"];
	    			$type = $value3["val2"];
	    			$val2 = $value3["val3"];
	    			$val5 = $value3["val5"];
	    			$val6 = $value3["val6"];
	    			$oldLine = $line;

	    			if (!is_array($search)) {
			    		$search = array($search);
			    	} 
			    	foreach ($search as $value) {
			    		self::$searchedWords[] = $value;
			    		$bandera2 = false;
			    		if (strlen($value)<=0) {
		    				continue;
		    			}
		    			if($type=="addBeforeLineByLine" && $numline==$value) {
		    				//echo $numline." - ".$value;
		    				$line = $val2."\r\n".$line;
		    				$bandera = true;
		    				$res1 = array();
		    		    	$res1["action"] = $type;
		    		    	$res1["lineNum"] = $numline;
		    		    	$res1["lineOld"] = trim($oldLine);
		    		    	$res1["lineNew"] = trim($line);
		    		    	$res1["search"] = $value;
		    		    	$res1["val2"] = $val2;
		    		    	$res[] = $res1;
		    			} else if($type=="changeLine" && $numline==$value) {
		    				//echo $numline." - ".$value;
		    				$line = $val2;
		    				$bandera = true;
		    				$res1 = array();
		    		    	$res1["action"] = $type;
		    		    	$res1["lineNum"] = $numline;
		    		    	$res1["lineOld"] = trim($oldLine);
		    		    	$res1["lineNew"] = trim($line);
		    		    	$res1["search"] = $value;
		    		    	$res1["val2"] = $val2;
		    		    	$res[] = $res1;
		    			}
		    			$pos1 = strpos($line, $value);
			    		if ( ($pos1 !== false) && ($type!="addBeforeLineByLine" && $type!="changeLine" ) ) { 
			    			if($type=="find") {
			    				$this->makeMovement($resPas, $value, $res, $numline, $line, $search, $bandera, $bandera2, $val2, $val5, $val6, function(&$res, $numline, &$line, $value, $search, &$bandera, $val2) {
			    					$res[$value]["Line: ".$numline] = trim($line);
			    				});
			    			} else if($type=="replace") {
			    				$this->makeMovement($resPas, $value, $res, $numline, $line, $search, $bandera, $bandera2, $val2, $val5, $val6, function(&$res, $numline, &$line, $value, $search, &$bandera, $val2) {
			    					$line = str_replace($search, $val2, $line);
			    					$bandera = true;
			    				});
			    				
			    			} else if($type=="replaceLineWhere") {
			    				$this->makeMovement($resPas, $value, $res, $numline, $line, $search, $bandera, $bandera2, $val2, $val5, $val6, function(&$res, $numline, &$line, $value, $search, &$bandera, $val2) {
			    					$line = $val2."\r\n";
			    					$bandera = true;
			    				});
			    			} else if($type=="addAfterLine") {
			    				$this->makeMovement($resPas, $value, $res, $numline, $line, $search, $bandera, $bandera2, $val2, $val5, $val6, function(&$res, $numline, &$line, $value, $search, &$bandera, $val2) {
			    					$line = $line.$val2."\r\n";
			    					$bandera = true;
			    				});
			    				
			    			} else if($type=="addBeforeLine") {
			    				$this->makeMovement($resPas, $value, $res, $numline, $line, $search, $bandera, $bandera2, $val2, $val5, $val6, function(&$res, $numline, &$line, $value, $search, &$bandera, $val2) {
			    					$line = $val2."\r\n".$line;
			    					$bandera = true;
			    				});
			    			}
			    		    if ($type!="find" && $bandera2) {
			    		    	$res1 = array();
			    		    	$res1["action"] = $type;
			    		    	$res1["lineNum"] = $numline;
			    		    	$res1["lineOld"] = trim($oldLine);
			    		    	$res1["lineNew"] = trim($line);
			    		    	$res1["search"] = $value;
			    		    	$res1["val2"] = $val2;
			    		    	$res[] = $res1;
			    		    }
			    		}
		    		}
	    		}
	    		if(
	    			$this->ifHasType($asincrono, "replace") || 
	    			$this->ifHasType($asincrono, "addAfterLine") || 
	    			$this->ifHasType($asincrono, "addBeforeLine") || 
	    			$this->ifHasType($asincrono, "addAtTheEnd") || 
	    			$this->ifHasType($asincrono, "replaceLineWhere") || 
	    			$this->ifHasType($asincrono, "addBeforeLineByLine")  || 
	    			$this->ifHasType($asincrono, "changeLine") 
	    		) {
		    		$text_file .= $line;
		    	}
		    }
		    if(
		    	$this->ifHasType($asincrono, "replace") || 
		    	$this->ifHasType($asincrono, "addAfterLine") || 
		    	$this->ifHasType($asincrono, "addBeforeLine") || 
		    	$this->ifHasType($asincrono, "addAtTheEnd") || 
		    	$this->ifHasType($asincrono, "replaceLineWhere") || 
	    		$this->ifHasType($asincrono, "addBeforeLineByLine")  || 
	    		$this->ifHasType($asincrono, "changeLine") 
		    ) {
		    	if ( $this->ifHasType($asincrono, "addAtTheEnd") ) {
		    		foreach ($asincrono as $value3) {
	    				$val2 = $value3["val3"];
	    				$type = $value3["val2"];
	    				if($type=="addAtTheEnd") {
	    					$text_file .= "\r\n".$val2;
		    				$bandera = true;
		    				$res1 = array();
		    		    	$res1["action"] = $type;
		    		    	$res1["lineNum"] = "";
		    		    	$res1["lineOld"] = "";
		    		    	$res1["lineNew"] = "";
		    		    	$res1["search"] = "";
		    		    	$res1["val2"] = $val2;
		    		    	$res[] = $res1;
	    				}
		    		}
		    	}
		    	if (self::$madeChanges) {
		    		file_put_contents($archivo, $text_file);
		    	}
				/*
				if (count($asincrono)==1) {
					fclose($handle);
					return $bandera;
				}*/
	    		
	    	}
	    	self::$searchedWords = array_unique(self::$searchedWords);
		    fclose($handle);
		    return $res;
		}
	}
	function makeMovement(&$resPas, $value, &$res, $numline, &$line, $search, &$bandera, &$bandera2, $val2, $val5, $val6, $functionpass) {
		if (isset($val5) && is_array($val5) && count($val5)>0 ) {
			if ($numline>=$val5["starts"] && $numline<=$val5["finish"]) {
				if ($val6!=false && is_numeric($val6)) {
					$resPas[$value][] = true;
					if (count($resPas[$value])==$val6) {
						$functionpass($res, $numline, $line, $value, $search, $bandera, $val2);
						$bandera2 = true;
					}
				} else {
					$functionpass($res, $numline, $line, $value, $search, $bandera, $val2);
					$bandera2 = true;
				}
			}
		} else {
			if ($val6!=false && is_numeric($val6)) {
				$resPas[$value][] = true;
				if (count($resPas[$value])==$val6) {
					$functionpass($res, $numline, $line, $value, $search, $bandera, $val2);
					$bandera2 = true;
				}
			} else {
				$functionpass($res, $numline, $line, $value, $search, $bandera, $val2);
				$bandera2 = true;
			}
		}
		
	}
	// This give you the line before starting a function, it will respect the comments made for the function, etc.
	function getFunctionInit($function) {
		$search = array("function $function(","function $function (");
		$res = $this->findAndReplace($search, "find");
		foreach ($res as $value) {
			foreach ($value as $key => $value2) {
				$line = str_replace("Line: ", "", $key);
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

	function getLineWhere($search) {
		$search = array($search);
		$res = $this->findAndReplace($search, "find");
		foreach ($res as $value) {
			foreach ($value as $key => $value2) {
				return str_replace("Line: ", "", $key);
				
			}
		}
		return false;
	}

	function getFunctionLines($function) {
		$search = array("function $function(","function $function (");
		$res = $this->findAndReplace($search, "find");
		foreach ($res as $value) {
			foreach ($value as $key => $value2) {
				$line = str_replace("Line: ", "", $key);
				
			}
		}
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
			    		$spac = explode("function", $liness);
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
			    $res = array("starts"=>$line, "finish"=>$finishLine);
			    return $res;
			}
		}
		return false;
	}

	function getIfLines($if, $lines=false, $pos=false) {
		$search = array($if);
		$res = $this->findAndReplace($search, "find", "", false, $lines, $pos);
		foreach ($res as $value) {
			foreach ($value as $key => $value2) {
				$line = str_replace("Line: ", "", $key);
				break 2;
			}
		}
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

	function count() {
		$archivo = self::$file;
		$handle = fopen($archivo, "r");
		if ($handle) { // process the line read.
			$numline = 0;
		    while (($liness = fgets($handle)) !== false) {
		    	$numline++;
		    }
		    return $numline;
		}
		return false;
	}
	function ifHasType($array, $type) {
		foreach ($array as $value3) {
			if ($value3["val2"] == $type) {
				return self::$pointer;
			}
		}
		return false;
	}
	function find($search, $lines=false, $pos=false) {
		if(self::$wait==false) 
			return $this->findAndReplace($search, "find", "", false, $lines, $pos);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "find";
		$res["val3"] = "";
		$res["val5"] = $lines;
		$res["val6"] = $pos;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function replace($search, $change, $lines=false, $pos=false) {
		if(self::$wait==false)
			return $this->findAndReplace($search, "replace", $change, false, $lines, $pos);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "replace";
		$res["val3"] = $change;
		$res["val5"] = $lines;
		$res["val6"] = $pos;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function replaceLineWhere($search, $change, $lines=false, $pos=false) {
		if(self::$wait==false)
			return $this->findAndReplace($search, "replaceLineWhere", $change, false, $lines, $pos);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "replaceLineWhere";
		$res["val3"] = $change;
		$res["val5"] = $lines;
		$res["val6"] = $pos;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function addAfterLine($search, $change, $lines=false, $pos=false) {
		if(self::$wait==false)
			return $this->findAndReplace($search, "addAfterLine", $change, false, $lines, $pos);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "addAfterLine";
		$res["val3"] = $change;
		$res["val5"] = $lines;
		$res["val6"] = $pos;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function addBeforeLine($search, $change, $lines=false, $pos=false) {
		if(self::$wait==false)
			return $this->findAndReplace($search, "addBeforeLine", $change, false, $lines, $pos);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "addBeforeLine";
		$res["val3"] = $change;
		$res["val5"] = $lines;
		$res["val6"] = $pos;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function addBeforeLineByLine($search, $change, $lines=false, $pos=false) {
		if(self::$wait==false)
			return $this->findAndReplace($search, "addBeforeLineByLine", $change, false, $lines, $pos);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "addBeforeLineByLine";
		$res["val3"] = $change;
		$res["val5"] = $lines;
		$res["val6"] = $pos;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function changeLine($search, $change) {
		if(self::$wait==false)
			return $this->findAndReplace($search, "changeLine", $change, false, false, false);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = $search;
		$res["val2"] = "changeLine";
		$res["val3"] = $change;
		$res["val5"] = false;
		$res["val6"] = false;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function addAtTheEnd($add) {
		if(self::$wait==false)
			return $this->findAndReplace("", "addAtTheEnd", $add);

		$res = array();
		$res["function"] = "findAndReplace";
		$res["val1"] = "";
		$res["val2"] = "addAtTheEnd";
		$res["val3"] = $add;
		$res["val5"] = false;
		$res["val6"] = false;
		self::$waitFunctions[] = $res;
		return self::$pointer;
	}

	function exists() {
		return file_exists(self::$file);
	}

	// Used to speed, all the changes will do it reading only one time
	function execute($madeChanges=true) {
		self::$madeChanges = $madeChanges;
		self::$wait = false;
		$res = $this->findAndReplace("", "", "", self::$waitFunctions);
		//var_dump(self::$waitFunctions);
		self::$waitFunctions = array();
		return $res;
	}
	function getSearchedWords() {
		return self::$searchedWords;
	}
}
?>