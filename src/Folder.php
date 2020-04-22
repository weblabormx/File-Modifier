<?php

namespace WeblaborMX\FileModifier;
use FilesystemIterator;
use DirectoryIterator;

class Folder {

	private $directory;

	public function __construct($directory) 
	{
		$this->directory = $directory;
	}

	// Inside functions

	private function getIterator($directory = null) 
	{
		if(is_null($directory))
			$directory = $this->directory;
		$elements = [];
		$object = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
		foreach ($object as $file) {
			$elements[] = $file;
			if($file->isDir()) // To make it recursively and pass for all folders inside
				$elements = array_merge($elements, $this->getIterator($directory.'/'.$file->getFilename()));
		}
		return $elements;
	}

	private function getPath($path) 
	{
		$path = str_replace($this->directory.'/', '', $path ); 
		$path = str_replace($this->directory.'\\', '', $path ); 
		return $path;
	}

	// Attributes

	public function count() 
	{
		$folders = 0;
		foreach( $this->getIterator() as $file) {
			if($file->isFile())
				$folders++; 
		}
		return $folders;
	}

	public function total_subfolders() 
	{
		$folders = 0;
		foreach( $this->getIterator() as $file) {
			if($file->isDir())
				$folders++; 
		}
		return $folders;
	}

	public function exists() 
	{
		if (file_exists($this->directory) && is_dir($this->directory))
			return true;
		return false;
	}

	public function files($complete_path = false) 
	{
		$folders = [];
		foreach( $this->getIterator() as $file) {
			if(!$file->isFile()) {
				continue;
			}
			if($complete_path) {
				$folders[] = $file->getPathname();
			} else {
				$folders[] = $this->getPath($file->getPathname());	
			}
		}
		return $folders;
	}

	public function directories($complete_path = false) 
	{
		$folders = [];
		foreach( $this->getIterator() as $file) {
			if(!$file->isDir()) {
				continue;
			}
			if($complete_path) {
				$folders[] = $file->getPathname();
			} else {
				$folders[] = $this->getPath($file->getPathname());	
			}
		}
		return $folders;
	}

	// Functions
	
	public function create() 
	{
		if($this->exists())
			return false;
		mkdir($this->directory, 0777, true);
		return true;
	}

	public function copyTo( $destiny ) 
	{
		if(Helper::folder($destiny)->exists())
			return false;
		Helper::folder($destiny)->create();
		foreach( $this->getIterator() as $file) {
			$path = $this->getPath($file->getPathname());
			if($file->isDir()) {
				Helper::folder("{$destiny}\\{$path}")->create();
				continue;
			}
			copy("{$this->directory}\\{$path}", "{$destiny}\\{$path}");
		}
		return true;
	}

	public function remove() 
	{
		if(!$this->exists())
			return true;
		exec('rmdir /s /q "'.$this->directory.'\"');
		return $this->remove(); // Until it is removed
	}

	public function moveTo( $destiny ) 
	{
		if(Helper::folder($destiny)->exists())
			return false;
		rename($this->directory, $destiny);
		return true;
	}

}
?>