<?php
namespace WeblaborMX\FileModifier;

class AnalyzeFilesGetFiles {

	private $rutas = array();

	function getFiles($rutas, $searchFor=array(), $searchIn=array(), $exactlymatch = false, $rutanameprinc = null)
	{	
		if(!is_array($rutas)) {
			$rutas = array($rutas);
		}
		if(!is_array($searchFor)) {
			$searchFor = array($searchFor);
		}
		foreach ($rutas as $ruta) {
			$rutaname = $ruta;
			if (isset($rutanameprinc)) {
				$rutaname = $rutanameprinc;
			}
			// abrir un directorio y listarlo recursivo
			if (is_dir($ruta)) {
				if ($dh = opendir($ruta)) {
					//echo "<br><strong>$ruta</strong><br>"; // Aqui se imprime el nombre de la carpeta o directorio
					while (($file = readdir($dh)) !== false) {
						if ($file!="." && $file!="..") { // Si se desea mostrar directorios y archivos
							if($file != '.svn') {
								if ( !is_dir($file) ) {
									if (count($searchFor)>0) {
										$esta = false;
										foreach ($searchFor as $value) {
											if ($exactlymatch) {
												if ($file==$value) {
													$this->rutas[$rutaname][] = $ruta.DIRECTORY_SEPARATOR.$file;
													break;
												}
											} else {
												if (Helper::endsWith($file, $value) || $file==$value) {
													$this->rutas[$rutaname][] = $ruta.DIRECTORY_SEPARATOR.$file;
													break;
												}
											}
											
										}
										
									} else {
										$this->rutas[$rutaname][] = $ruta.DIRECTORY_SEPARATOR.$file;
									}
									
								} 
								if (count($searchIn)>0) {
									foreach ($searchIn as $value) {
										//echo "endsWith(".$file.", ".$value.")<br />";
										if (Helper::endsWith($file, $value) || $file==$value) {
											//echo '$this->getFiles('.$ruta.DIRECTORY_SEPARATOR.$file.', $searchFor, $searchIn)';
											$this->getFiles($ruta.DIRECTORY_SEPARATOR.$file, $searchFor, $searchIn, $exactlymatch, $rutaname); // Ahora volvemos a llamar la función
											break;
										}
									}
								} else {
									$this->getFiles($ruta.DIRECTORY_SEPARATOR.$file, $searchFor, $searchIn, $exactlymatch, $rutaname); // Ahora volvemos a llamar la función
								}
						    }
						}
					}
					closedir($dh);
				}
			}
		}
		return $this->rutas;
	}

}
?>