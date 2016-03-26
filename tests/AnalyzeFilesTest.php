<?php
use \WeblaborMX\FileModifier\AnalyzeFiles;
include_once 'tests/loader.php';

class AnalyzeFilesTest extends \PHPUnit_Framework_TestCase {

	private $directory;

	public function setUp() {

		$this->directory = 'examples/folder';

    }

    // Example of return
	/*
        $result = array(
            'sub1/sub1.php' => array( // File name
                0 => array( // Number of action
                    'result'        => true,
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
                    ),
                    'results' => array(
                        0 => array(
                            'action'    => 'replace',
                            'lineNum'   => 1,
                            'lineOld'   => 'user1,pass1',
                            'lineNew'   => 'usuario11,pass1',
                            'search'    => 'user',
                            'val2'      => 'usuario1'
                        )
                    )
                )
            )
        );
    */

    public function testBasicExample() {

        $results = AnalyzeFiles::directory($this->directory)->execute(function($file) {
            $file->fileIs('sub1/sub1.php');
            $file->action(function($action) {
                $action->whereNoSearch('Carlos');
                $action->whereSearch('Jorge');
                $action->replace('Jorge', 'Carlos');
            });
            $file->action(function($action) {
                $action->whereSearch('Example1');
                $action->replace('Hi', 'Bye');
            });
        }, false); // For no executing

    	$this->assertTrue( is_array($results) );
        $this->assertTrue( is_array($results['sub1/sub1.php']) );
        $this->assertEquals( 2, count($results['sub1/sub1.php']) );     // Two actions
    	$this->assertTrue( isset($results['sub1/sub1.php'][0]['requirements'][0]['function']) );
        $this->assertTrue( isset($results['sub1/sub1.php'][0]['requirements'][0]['value']) );

    }

}
?> 