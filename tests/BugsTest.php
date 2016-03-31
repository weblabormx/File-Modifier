<?php
use \WeblaborMX\FileModifier\FileModifier;
include_once 'tests/loader.php';

class BugsTest extends \PHPUnit_Framework_TestCase {

	private $file;

	public function testBugFunctionsLines() {

		$lines = FileModifier::file( 'examples/usuarios.php' )->getFunctionLines('database');
	    $this->assertEquals(33, $lines['starts']);
	    $this->assertEquals(39, $lines['finish']);

		$lines = FileModifier::file( 'examples/controller.php' )->getFunctionLines('getList');
	    $this->assertEquals(52, $lines['starts']);
	    $this->assertEquals(57, $lines['finish']);

	    $lines = FileModifier::file( 'examples/controller.php' )->getFunctionLines('getSee');
	    $this->assertFalse($lines);

	}


}
?> 