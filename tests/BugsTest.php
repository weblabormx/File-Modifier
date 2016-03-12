<?php
use \WeblaborMX\FileModifier\FileModifier;
include_once 'tests/loader.php';

class BugsTest extends \PHPUnit_Framework_TestCase {

	private $file;

	public function setUp() {

		$this->file = 'examples/usuarios.php';

    }

	public function testBugFunctionsLines() {

		$lines = FileModifier::file( $this->file )->getFunctionLines('database');
	    $this->assertEquals($lines['starts'], 33);
	    $this->assertEquals($lines['finish'], 39);

	}


}
?> 