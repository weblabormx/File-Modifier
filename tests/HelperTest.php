<?php
use \WeblaborMX\FileModifier\Helper;
include_once 'tests/loader.php';

class HelperTest extends \PHPUnit_Framework_TestCase {

	public function testStartsWith() {

		$this->assertTrue( Helper::startsWith( 'hola-mundo.php', 'hola' ));
		$this->assertFalse( Helper::startsWith( 'adios-mundo-hola.php', 'hola' ));
		$this->assertFalse( Helper::startsWith( 'ahola-mundo.php', 'hola' ));

	}

	public function testEndsWith() {

		$this->assertTrue( Helper::endsWith( 'hola-mundo.php', '.php' ));
		$this->assertFalse( Helper::endsWith( 'file.js', '.php' ));
		
	}

	public function testHasString() {

		$this->assertTrue( Helper::hasString( 'hola-mundo.php', 'mundo' ));
		$this->assertTrue( Helper::hasString( 'adios-mundo-hola.php', 'hola' ));
		$this->assertTrue( Helper::hasString( 'ahola-mundo.php', 'hola' ));
		$this->assertFalse( Helper::hasString( 'ahola-mundo.php', 'adios' ));
		
	}

}
?> 