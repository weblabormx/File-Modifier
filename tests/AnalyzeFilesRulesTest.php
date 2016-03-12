<?php
use \WeblaborMX\FileModifier\AnalyzeFiles;
include_once 'src/AnalyzeFiles.php';
include_once 'src/AnalyzeFilesRules.php';
include_once 'src/AnalyzeFilesSingleRule.php';
include_once 'src/AnalyzeFilesSingleRuleValidation.php';
include_once 'src/AnalyzeFilesGetFiles.php';
include_once 'src/FileModifier.php';
include_once 'src/Helper.php';

class AnalyzeFilesRulesTest extends \PHPUnit_Framework_TestCase {

	private $directory;

	public function setUp() {

		$this->directory = 'examples/folder';

    }

    // Example of return
	/* 
		$results = array(
			'example1.php' => true,
			'example2.php' => true,
		);
	*/

    public function testAllTrue() {

    	// All true
    	$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    		$rules->add(function($data) {
    			$data->fileIs('example1.php');
    			$data->validation(function($validation) {
    				$count = 1;
	    			$validation->whereSearch('Carlos', $count); 
	    			$validation->whereSearch('Example1'); // Without $count only search that the search exists minimum once
    			});
    			
    		});
    		$rules->add(function($data) {
    			$data->fileIs('example2.php');
    			$data->validation(function($validation) {
    				$count = 2;
	    			$validation->whereSearch('Carlos',$count);
    			});
    		});
    	});
    	$this->assertTrue( is_array($results) );
    	$this->assertTrue( $results['example1.php'] );
    	$this->assertTrue( $results['example2.php'] );

    }

    function testMixedResults() {

    	// Mixed results
    	$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    		$rules->add(function($data) {
    			$data->fileIs('example1.php');
    			$data->validation(function($validation) {
    				$count = 1;
	    			$validation->whereSearch('Carlos', $count); 
	    			$validation->whereSearch('Example1'); // Without $count only search that the search exists minimum once
    			});
    		});
    		$rules->add(function($data) {
    			$data->fileIs('example2.php');
    			$data->validation(function($validation){
    				$count = 1; // Here we have an error
	    			$validation->whereSearch('Carlos',$count);
    			});
	    			
    		});
    	});
    	$this->assertTrue( is_array($results) );
    	$this->assertTrue( $results['example1.php'] );
    	$this->assertFalse( $results['example2.php'] );

    }

    function testSearchByTermination() {

    	// Searching some files at the same time searching by the termination
    	$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    		$rules->add(function($data) {
    			$data->fileEndsWith('.php');
    			$data->validation(function($validation) {
    				$validation->whereSearch('Carlos'); 
    			});
    		});
    	});
    	$this->assertTrue( is_array($results) );
    	$this->assertTrue( $results['example1.php'] );
    	$this->assertTrue( $results['example2.php'] );

    }

    function testSearchByListOfFiles() {

    	// Searching some files at the same time searching by the termination
    	$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    		$rules->add(function($data) {
    			$data->files(array('example1.php','example2.php'));
    			$data->validation(function($validation) {
    				$validation->whereSearch('Carlos'); 
    			});
    		});
    	});
    	$this->assertTrue( is_array($results) );
    	$this->assertTrue( $results['example1.php'] );
    	$this->assertTrue( $results['example2.php'] );

    }

    function testFromTwoFolders() {

    	// Searching some files at the same time searching by the termination
    	$directories = array(	
    		'examples/folder',
    		'examples/folder2'
    	);
    	$results = AnalyzeFiles::rules()->directory($directories)->create(function($rules) {
    		$rules->add(function($data) {
    			$data->fileEndsWith('.php');
    			$data->validation(function($validation) {
    				$validation->whereSearch('Carlos'); 
    			});
    		});
    	});
    	$this->assertTrue( is_array($results) );
    	$this->assertTrue( $results['examples/folder/example1.php'] );
    	$this->assertTrue( $results['examples/folder/example2.php'] );
    	$this->assertTrue( $results['examples/folder2/example1.php'] );

    }

    function testSelectSubdirectories() {

    	$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    		$rules->add(function($data) {
    			$data->fileEndsWith('.php');
    			$data->filesInside(array('sub1','sub2'));
    			$data->validation(function($validation) {
    				$validation->whereSearch('Carlos'); 
    			});
    		});
    	});
    	$this->assertTrue( is_array($results) );
    	$this->assertFalse( $results['sub1/sub1.php'] );
    	$this->assertTrue( $results['sub2/sub2.php'] );

    }

    /*

    
	public function testReplaceAWord() {

		// We will replace the word 'Carlos' for 'Jorge'
		$results = AnalyzeFiles::directory($this->directory)
			->whereFilesEndsWith('.php')
			->do(function($data, $file) {
				$data->whereExist('Carlos');
				$data->replace('Jorge');
			})->results();

		$this->assertTrue( is_array($results) );
		/*
			$result = array(
				'example1.php' => array(
					'search'		=> 'Carlos',
					'replacement'	=> 'Jorge',
					'count'			=> 1,
					'data'			=> array(
						0 => array(
							'before' 	=> 'echo \'Hi Carlos\';',
							'after'		=> 'echo \'Hi Jorge\';'
						)
					)
				)
			);
		*//*
		$this->assertTrue( FileModifier::file('composer.json')->exists() );
		$this->assertFalse( FileModifier::file('holamundo.php')->exists() );

	}*/

}
?> 