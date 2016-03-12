<?php
use \WeblaborMX\FileModifier\AnalyzeFiles;
include_once 'tests/loader.php';

class AnalyzeFilesRulesTest extends \PHPUnit_Framework_TestCase {

	private $directory;

	public function setUp() {

		$this->directory = 'examples/folder';

    }

    // Example of return
	/* 
		$results = array(
			'example1.php' => array(
                'result'    => true,
                'requirements'  => array(
                    0 => array(
                        'function'    => 'NoSearch',
                        'value'       => 'Carlos',
                        'result'      => true
                    ), 1 => array(
                        'function'    => 'Search',
                        'value'       => 'Jorge',
                        'result'      => true
                    )
                )
            )
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
        $this->assertTrue( is_array($results['example1.php']) );
    	$this->assertTrue( $results['example1.php']['result'] );
    	$this->assertTrue( $results['example2.php']['result'] );
        $this->assertTrue( isset( $results['example2.php']['requirements'][0]['function'] ) );
        $this->assertTrue( isset( $results['example2.php']['requirements'][0]['value'] ) );
        $this->assertTrue( isset( $results['example2.php']['requirements'][0]['result'] ) );

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
    	$this->assertTrue( $results['example1.php']['result'] );
    	$this->assertFalse( $results['example2.php']['result'] );

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
    	$this->assertTrue( $results['example1.php']['result'] );
    	$this->assertTrue( $results['example2.php']['result'] );

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
    	$this->assertTrue( $results['example1.php']['result'] );
    	$this->assertTrue( $results['example2.php']['result'] );

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
    	$this->assertTrue( $results['examples/folder/example1.php']['result'] );
    	$this->assertTrue( $results['examples/folder/example2.php']['result'] );
    	$this->assertTrue( $results['examples/folder2/example1.php']['result'] );

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
    	$this->assertFalse( $results['sub1/sub1.php']['result'] );
    	$this->assertTrue( $results['sub2/sub2.php']['result'] );

    }

}
?> 