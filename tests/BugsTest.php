<?php
use \WeblaborMX\FileModifier\FileModifier;
include_once 'tests/loader.php';

class BugsTest extends \PHPUnit_Framework_TestCase {

	private $file;

	public function testBugFunctionsLines() {

		$lines = FileModifier::file( 'examples/usuarios.php' )->getFunctionLines('database');
	    $this->assertEquals($lines['starts'], 33);
	    $this->assertEquals($lines['finish'], 39);

		$lines = FileModifier::file( 'examples/controller.php' )->getFunctionLines('getList');
	    $this->assertEquals($lines['starts'], 52);
	    $this->assertEquals($lines['finish'], 57);

	    $lines = FileModifier::file( 'examples/controller.php' )->getFunctionLines('getSee');
	    $this->assertFalse($lines);

	}


}
?> 